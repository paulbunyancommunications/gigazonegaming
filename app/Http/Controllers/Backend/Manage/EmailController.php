<?php


namespace App\Http\Controllers\Backend\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\GameRequest;
use App\Http\Requests\Request;
use App\Models\Championship\Game;
use App\Models\Championship\Player;
use App\Models\Championship\PlayerRelation;
use App\Models\Championship\PlayerRelationable;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class EmailController extends Controller
{
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
        $emails = '';
        $emailsUserRequest = '';
        $relations = '';
        list($separator, $game, $tournament, $team, $player) = $this->cleanPostToGetEmails($_POST);
        if (isset($_POST["get_game"]) and $game) {
            $theGame = Game::where('id', '=', $game)->first();
            $relations = $this->returnErrorsIfNull($theGame, $_POST);
            list($emailsUserRequest, $emails) = $this->getEmail($relations, $separator);
        } elseif (isset($_POST["get_tournament"]) and $tournament) {
            $theTournament = Tournament::where('id', '=', $tournament)->first();
            $relations = $this->returnErrorsIfNull($theTournament, $_POST);
            list($emailsUserRequest, $emails) = $this->getEmail($relations, $separator);
        } elseif (isset($_POST["get_team"]) and $team) {
            $theTeam = Team::where('id', '=', $team)->first();
            $relations = $this->returnErrorsIfNull($theTeam, $_POST);
            list($emailsUserRequest, $emails) = $this->getEmail($relations, $separator);
        } elseif (isset($_POST["get_player"]) and $player) {
            $player = Player::where('id', '=', $player)->first();
            $this->returnErrorsIfNull($player, $_POST);
            $emailsUserRequest = $emails = $player->email;
        }
        if($game or $tournament or $team or $player) {
            return View::make('game.emailForm')->with('sorts', $_POST)->with('email_get', $emails)->with('email_send', $emailsUserRequest);
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
//        $email_get
//        $user_subject
//        $user_message
//        $email_send
        list($subject, $message, $to, $toUser, $headers, $errors) = $this->cleanEmailPost($_POST);
        if (isset($_POST["send"]) and $_POST["send"] == "Send Email") {
            if ($errors == '') {
                $this->sendEmail($to, $subject, $message, $headers);
                return redirect('manage/email')->with('success', "The email has being sent");
            } else {
                return View::make('game.emailform')
                    ->with('error', $errors)
                    ->with('email_get', $toUser)
                    ->with('user_subject', $subject)
                    ->with('user_message', $message)
                    ->with('email_send', $to);
            }
        }else {
            return redirect('/manage/email/')->with('error', "No Player email address found");
        }
    }

    private function checkSeparator($separator, $space)
    {
        $sep = '';
        if ($separator == 'comma') {
            $sep = ',';
        } elseif ($separator == 'semicolon') {
            $sep = ';';
        } elseif ($separator == 'colon') {
            $sep = ':';
        } elseif ($separator == 'period') {
            $sep = '.';
        } elseif ($separator == 'plus') {
            $sep = '+';
        } elseif ($separator == 'minus') {
            $sep = '-';
        } elseif ($separator == 'vbar') {
            $sep = '|';
        } elseif ($separator == 'under') {
            $sep = '_';
        }
        if ($space == 'yes') {
            $sep .= ' ';
        }
        return $sep;
    }

    private function getEmail($players, $separator)
    {
        $emailsSend = '';
        $emails = '';
        foreach ($players as $k => $player) {
            $e = Player::where('id', '=', $player['player_id'])->first();
            if ($e != null) {
                $emails .= $e->email . $separator;
                $emailsSend .= $e->email . ", ";
            }
        }
        return array(trim(trim(trim($emailsSend, ", "), ']'), '['), trim(trim(trim($emails, $separator), ']'), '['));
    }

    /**
     * @param $to
     * @param $subject
     * @param $message
     * @param $headers
     */
    private function sendEmail($to, $subject, $message, $headers)
    {
        if(!is_array($to)){
            $to = explode(', ', $to);
        }
        foreach ($to as $k => $value) {
            //mailingFunction::send($value,$subject,$message,$headers);
        }
    }

    /**
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
//        // To send HTML mail, the Content-type header must be set
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
//
//        // Additional headers
        $headers .= 'To: ' . $to . "\r\n";
        $headers .= 'From: Gigazone Gaming <gigazonegaming@gmail.com>' . "\r\n";
//        $headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
        $headers .= 'Bcc: gigazonegaming@gmail.com' . "\r\n";
        return array($subject, $message, $to, $toUser, $headers, $errors);
    }

    /**
     * @return array
     */
    private function cleanPostToGetEmails($thePost)
    {
        $separator = ',';
        $space = 'yes';
        $game = '';
        $tournament = '';
        $team = '';
        $player = '';

        if (isset($thePost['separator'])) {
            $separator = $this->checkIfTheValuesAreValidReturnValueOrReturnFalse($thePost['separator']);
        }
        if (isset($thePost['space'])) {
            $space = $this->checkIfTheValuesAreValidReturnValueOrReturnFalse($thePost["space"]);
        }
        if ($separator and $space) {
            $separator = $this->checkSeparator($separator, $space);
        } else {
            $separator = ", ";
        }
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
