<?php


namespace App\Http\Controllers\Backend\Manage;

use App\Http\Controllers\Controller;
use App\Models\Championship\Game;
use App\Models\Championship\Player;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Illuminate\Support\Facades\Config;
use Pbc\FormMail\Facades\FormMailHelper;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use League\CommonMark\CommonMarkConverter;

class EmailController extends Controller
{
    protected $sentErrors = [];

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function email()
    {
        return View::make('game.email');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function email_get()
    {
        $name = '';
        $ids = '';
        list($separator, $game, $tournament, $team, $player) = $this->cleanPostToGetEmails($_POST); //clean all the emails
        if (isset($_POST["get_game"]) and $game) { //if user want to get all players on a game
            $theGame = Game::where('id', '=', $game)->first();
            $relations = $this->returnErrorsIfNull($theGame, $_POST);
            list($ids,$name) = $this->getEmail($relations, $separator);
        } elseif (isset($_POST["get_tournament"]) and $tournament) {//else if user want to get all players on a tournament
            $theTournament = Tournament::where('id', '=', $tournament)->first();
            $relations = $this->returnErrorsIfNull($theTournament, $_POST);
            list($ids,$name)  = $this->getEmail($relations, $separator);
        } elseif (isset($_POST["get_team"]) and $team) {//else if user want to get all players on a team
            $theTeam = Team::where('id', '=', $team)->first();
            $relations = $this->returnErrorsIfNull($theTeam, $_POST);
            list($ids,$name)  = $this->getEmail($relations, $separator);
        } elseif (isset($_POST["get_player"]) and $player) {//else if user want to get just one player
            $player = Player::where('id', '=', $player)->first();
            $relations = $this->returnErrorsIfNull($player, $_POST);
            $name = $player->name;
            $ids = $player->id;
        }
        if($game or $tournament or $team or $player) { //if we see at least one player selected we can send an email otherwise return an error
            return View::make('game.emailForm')->with('names_get', $name)->with('ids_get', $ids);
        }else{
            return Redirect::back()
                ->with('error', 'Select a game, tournament, team or player.')
                ->with('sorts', $_POST);
        }
     }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function email_send()
    {
        list($subject, $message, $to, $toUser, $errors) = $this->cleanEmailPost($_POST); //clean the post so
        if (isset($_POST["send"]) and $_POST["send"] == "Send Email") {
            if ($errors == '') {
                $converter = new CommonMarkConverter();
                $message =  $converter->convertToHtml($message);
                $sent = $this->sendEmail($to, $subject, $message);
                if (!$sent || count($this->sentErrors) > 0) {
                    return redirect('/manage/email/')->with(
                        'error',
                        ( $sent === 0
                            ? 'The email was sent to no recipients.'
                            : 'The email has being sent to '. $sent.' recipient'. ($sent > 1 ? 's' : null).'.'
                        )  .
                        (count($this->sentErrors) > 0
                            ? ' There were errors. '.implode('. ', $this->sentErrors)
                            : null
                        )
                    );
                }
                return redirect('/manage/email/')->with('success', "The email has being sent to ". $sent.' recipient'. ($sent > 1 ? 's' : null) .'!');
            } else {
                return View::make('game.emailForm')
                    ->with('error', $errors)
                    ->with('names_get', $_POST['emails'])
                    ->with('user_subject', $_POST['subject'])
                    ->with('user_message', $_POST["message"])
                    ->with('ids_get', $_POST['emailList']);
            }
        }elseif(isset($_POST["preview"]) and $_POST["preview"] == "Preview Email") {
            $converter = new CommonMarkConverter();
            $message = $converter->convertToHtml($message);
            return View::make('game.emailForm')
                ->with('error', $errors)
                ->with('names_get', $_POST['emails'])
                ->with('user_subject', $_POST['subject'])
                ->with('user_message', $_POST["message"])
                ->with('preview_message', $message)
                ->with('ids_get', $_POST['emailList']);

        }else {
            return redirect('/manage/email/')->with('error', "No Player email address found");
        }
    }

    private function getEmail($players, $separator)
    {
        $ids = '';
        $username = '';
        foreach ($players as $k => $player) {
            $e = Player::where('id', '=', $player['player_id'])->first();
            if ($e != null) {
                $ids .= $e->id . $separator;
                if($e->username!=''){
                $username .= $e->username . $separator;
                } elseif ($e->name!=''){
                    $username .= $e->name . $separator;
                }
            }
        }
        return array(trim(trim(trim(trim(trim($ids, $separator), ']'), '['), $separator)),trim(trim(trim(trim(trim($username, $separator), ']'), '['), $separator)));
    }

    /**
     * @param $to
     * @param $subject
     * @param $message
     * @return int
     */
    private function sendEmail($to, $subject, $message)
    {
        if(!is_array($to)){
            $to = explode(', ', $to);
        }
        $emailSendCount = 0;
        foreach ($to as $k => $value) {
            $player = Player::where('id', '=', $value)->first();
            $email = $player->email;
            $name = $player->name;
            if($name =='' or $name==null){
                $name = $player->username;
            }
            try {
                FormMailHelper::makeMessage([
                    'sender' => 'contact_us@' . str_replace_first('www.', '',
                            parse_url(Config::get('app.url'), PHP_URL_HOST)),
                    'recipient' => $email,
                    'name' => $name,
                    'subject' => [
                        'recipient' => $subject,
                    ],
                    'head' => [
                        'recipient' => $message,
                    ],
                    'body' => $message,
                ]);
                $emailSendCount++;
            } catch (\Exception $ex) {
                array_push($this->sentErrors, $ex->getMessage());
            }
        }

        return $emailSendCount;
    }

    /**
     * @param $thePost
     * @return array
     */
    private function cleanEmailPost($thePost)
    {
        $errors = false;
        $subject = '';
        if (isset($thePost['subject']) and trim($thePost['subject']) != '') {
            $subject = $thePost['subject'];
        }
        $message = '';
        if (isset($thePost['message']) and trim($thePost['message']) != '') {
            $message = $thePost['message'];
        }
        $to = '';
        if (isset($thePost['emailList']) and trim($thePost['emailList']) != '') {
            $to = $thePost['emailList'];
        }
        $toUser = '';
        if (isset($thePost['emails']) and trim($thePost['emails']) != '') {
            $toUser = $thePost['emails'];
        }
        if ($subject == '') {
            $errors .= "Please add a Subject. ";
        }
        if ($message == '') {
            $errors .= "Please add a Message. ";
        }
        if ($to == '') {
            $errors .= "Please add at least a Recipient. ";
        }
        return array($subject, $message, $to, $toUser, $errors);
    }

    /**
     * @param $thePost
     * @return array
     */
    private function cleanPostToGetEmails($thePost)
    {
        $separator = ', ';
        $game = '';
        $tournament = '';
        $team = '';
        $player = '';

        if (isset($thePost['game_sort'])) {
            $game = $this->checkIfTheValuesAreValidReturnValueOrReturnFalse($thePost["game_sort"]);
        }
        if (isset($thePost['tournament_sort'])) {
            $tournament = $this->checkIfTheValuesAreValidReturnValueOrReturnFalse($thePost["tournament_sort"]);
        }
        if (isset($thePost['team_sort'])) {
            $team = $this->checkIfTheValuesAreValidReturnValueOrReturnFalse($thePost["team_sort"]);
        }
        if (isset($thePost['player_sort'])) {
            $player = $this->checkIfTheValuesAreValidReturnValueOrReturnFalse($thePost["player_sort"]);
        }
        return array($separator, $game, $tournament, $team, $player);
    }

    private function returnErrorsIfNull($theObject, $thePost)
    {
        if ($theObject == null) {
            return Redirect::back()
                ->with('error', 'Select a game, tournament, team or player.')
                ->with('sorts', $thePost);
        }
        if(isset($thePost["get_player"])){return $theObject;}
        return $theObject->findPlayersRelations()->get()->toArray();
    }

    private function checkIfTheValuesAreValidReturnValueOrReturnFalse($value)
    {
        $value = trim($value);
        if ($value != '' and $value != '---') {
            return $value;
        }
        return False;
    }
}
