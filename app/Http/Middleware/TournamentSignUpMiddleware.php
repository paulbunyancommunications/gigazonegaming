<?php

namespace App\Http\Middleware;

use App\Models\Championship\Player;
use App\Models\Championship\Relation\PlayerRelation;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Closure;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Pbc\Bandolier\Type\Numbers;
use Illuminate\Http\Request;

class TournamentSignUpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $forCheck = new Request();
        $forCheck2 = $forCheck;
        $forCheck2->replace([]);
        if($request === [] or $request === $forCheck or $request === $forCheck2){
            return $this->error("There was no real request here.... moving on!");
        }
        if($request->input('tournament') !== $request->route()->getName()) {
            return $this->error('Tournament route mismatch');
        }
        $theRequests = $request->all();
        $getTournament = Tournament::where('name', $request->route()->getName());
        if(!$getTournament->exists()) {
            return $this->error('The tournament submitted does not exist');
        }
        /** @var Tournament $tournament */
        $tournament = $getTournament->first();

        // get the form validation rules and check for errors
        $rules = [];
        foreach (json_decode($tournament->sign_up_form, true) as $key => $value) {
            $rules[$key] = $value[1];
        }
        $validator = \Validator::make($theRequests, $rules);
        if($validator->fails()) {
            // format returned messages
            $returnedErrors = [];
            $form = json_decode($tournament->sign_up_form, true);
            foreach ($form as $key => $value) {
                foreach ($validator->messages()->get($key) as $message) {
                    array_push($returnedErrors, str_replace_first($key, $value[0], $message));
                }
            }
            // fix any secondary keys
            foreach (array_reverse($form) as $key => $value) {
                foreach ($returnedErrors as $subkey => $message) {
                    $returnedErrors[$key] = str_replace_first($key, $value[0], $returnedErrors[$key]);
                }
            }
            return $this->error($returnedErrors);
        }
        $teams = $tournament->Teams()->get();
        $playerExist = [];
        $usernameExists = [];

        // for if the the main contact exists as a player in the system already
        if (isset($theRequests['email']) AND Player::where('email', '=', $theRequests['email'])->exists()) {
            $id = Player::where('email', '=', $theRequests['email'])->first()->id;
            $playerExist[$theRequests['email']] =  $id;
        }
        // if main contact user name was submitted then check if it already exists
        if (isset($theRequests['username']) AND Player::where('username', '=', $theRequests['username'])->exists()) {
            $email = Player::where('username', '=', $theRequests['username'])->first()->email;
            $username =  $theRequests['username'];
            $usernameExists[$email] =  $username;
        }
        // run through all the other teammates and alternates and check if they exist
        for($i=1; $i < $tournament->max_players; $i++) {
            if (isset($theRequests['teammate-'.Numbers::toWord($i).'-email']) AND Player::where('email', '=', $theRequests['teammate-' . Numbers::toWord($i) . '-email'])->exists()) {
                $id = Player::where('email', '=', $theRequests['teammate-' . Numbers::toWord($i) . '-email'])->first()->id;
                $email = $theRequests['teammate-'.Numbers::toWord($i).'-email'];
                $playerExist[$email] = $id;
            }//if not dont even push it to the array so we run faster through each
            if (isset($theRequests['alternate-' . Numbers::toWord($i) . '-email']) AND Player::where('email', '=', $theRequests['alternate-' . Numbers::toWord($i) . '-email'])->exists()) {
                $id =  Player::where('email', '=', $theRequests['alternate-' . Numbers::toWord($i) . '-email-address'])->first()->id;
                $email =  $theRequests['alternate-' . Numbers::toWord($i) . '-email-address'];
                $playerExist[$email] =  $id;
            }//if not dont even push it to the array so we run faster through each
            if (isset($theRequests['teammate-'.Numbers::toWord($i).'-username']) AND Player::where('username', '=', $theRequests['teammate-' . Numbers::toWord($i) . '-username'])->exists()) {
                $email = Player::where('username', '=', $theRequests['teammate-' . Numbers::toWord($i) . '-username'])->first()->email;
                $username = $theRequests['teammate-'.Numbers::toWord($i).'-username'];
                $usernameExists[$email] = $username;
            }
            if (isset($theRequests['alternate-' . Numbers::toWord($i) . '-username']) AND Player::where('username', '=', $theRequests['alternate-' . Numbers::toWord($i) . '-username'])->exists()) {
                $email =  Player::where('username', '=', $theRequests['alternate-' . Numbers::toWord($i) . '-username'])->first()->email;
                $username =  $theRequests['alternate-' . Numbers::toWord($i) . '-username'];
                $usernameExists[$email] =  $username;
            }
        }

        $repeatedPlayers = [];
        //this is because players could be also players in other
        // tournaments or games but if there are  no teams in
        // this tournament to go through, same players
        // will generate an error, in this way it wont.
        $teamExist = false;
        // here we will check if the player already has a
        // team in the same tournament in which they are
        // signing in.
        if(count($playerExist) > 0) {
            foreach ($playerExist as $email => $p_id) {
                foreach ($teams as $team) {
                    $teamExist = true;
                    $relation = PlayerRelation::where([
                        ['relation_type', "=", Team::class],
                        ['relation_id', "=", $team->id],
                        ['player_id', '=', $p_id]
                    ])->exists();
                    if ($relation) {
                        $repeatedPlayers[] = $email;
                    } elseif (isset($usernameExists[$email])) {
                        unset($usernameExists[$email]);
                    }
                }
                if(!$teamExist and isset($usernameExists[$email])){
                    unset($usernameExists[$email]);
                }
            }
        }
        $error = [];
        if ($tournament === null) {
            $error[]= trans('tournament.not_found', ['tournament' => $this->getTournament()]);
        }
        if (count($repeatedPlayers)>0 ){
            $error[]= trans('email.unique', $repeatedPlayers);
            foreach ($repeatedPlayers as $k => $v){
                $error[]= trans('-- '. $v, $repeatedPlayers);
            }
        }
        if( count($usernameExists)>0 ) {
            $error[]= trans('Your user name already exists in the database', $usernameExists);
            foreach ($usernameExists as $k => $v){
                $error[]= trans('-- '. $v, $usernameExists);
            }
        }
        if (count($error)!=0) {
            return $this->error($error);
        }
        try {
            DB::beginTransaction();
            // make new team
            $team = new Team();
            if(Team::where([['name', '=', $request->input('team-name')],['tournament_id', '=', $tournament->id]])->exists()){
                return $this->error("Team Name is already in the db");
            }else {
                $team->tournament_id = $tournament->id;
                $team->name = $request->input('team-name');
                $team->save();
            }


            // add captain
            if( Player::where('email', '=', $theRequests['email'])->exists()){
                $captain = Player::where('email', '=', $theRequests['email'])->first();
            } else {
                $captain = new Player();
                if($request->has('username') and $request->has('username')!='' and $request->has('username')!= null){
                    $captain->username = $request->input('username');
                }else{
                    $username = $this->createRandomNameOrUsername();
                    $captain->username = $username;
                }
                $captain->email = $request->input('email');
                $captain->name = $request->input('name');
                $captain->phone = $request->input('phone');
                $captain->save();
            }

            // add captain to team/tournament/game
            $captain::createRelation([
                'player' => $captain,
                'game' => $tournament->game,
                'team' => $team,
            ]);

            // add captain and save the team
            $team->captain = $captain->id;
            $team->save();

            // add other players
            for ($i = 1; $i < $tournament->max_players; $i++) {
                if (($request->input('teammate-' . Numbers::toWord($i) . '-name')
                     OR $request->input('teammate-' . Numbers::toWord($i) . '-username'))
                    AND filter_var($request->input('teammate-' . Numbers::toWord($i) . '-email'), FILTER_VALIDATE_EMAIL)
                ) {
                    if( Player::where('email', '=', $theRequests['teammate-' . Numbers::toWord($i) . '-email'])->exists()){
                        $player = Player::where('email', '=', $theRequests['teammate-' . Numbers::toWord($i) . '-email'])->first();
                    } else {
                        $player = new Player();
                        if($request->has('teammate-' . Numbers::toWord($i) . '-username') and $request->input('teammate-' . Numbers::toWord($i) . '-username')!='' and $request->input('teammate-' . Numbers::toWord($i) . '-username')!=null) {
                            $player->username = $request->input('teammate-' . Numbers::toWord($i) . '-username');
                        }else{
                             $username = $this->createRandomNameOrUsername();
                            $player->username = $username;
                        }
                        if($request->has('teammate-' . Numbers::toWord($i) . '-name') and $request->input('teammate-' . Numbers::toWord($i) . '-name')!='' and $request->input('teammate-' . Numbers::toWord($i) . '-name')!=null) {
                            $player->name = $request->input('teammate-' . Numbers::toWord($i) . '-name');
                        }else{
                            $name = $this->createRandomNameOrUsername();
                            $player->name = $name;
                        }
                        $player->email = $request->input('teammate-' . Numbers::toWord($i) . '-email');
                        $player->save();
                    }
                    // attach player to team/tournament/game
                    $player::createRelation([
                        'player' => $player,
                        'tournament' => $tournament,
                        'game' => $tournament->game,
                        'team' => $team,
                    ]);
                }
            }
            // add other players alternate-###-email alternate-###-username
            for ($i = 1; $i < $tournament->max_players; $i++) {
                if (($request->input('alternate-' . Numbers::toWord($i) . '-name')
                        OR $request->input('alternate-' . Numbers::toWord($i) . '-username'))
                    AND filter_var($request->input('alternate-' . Numbers::toWord($i) . '-email'), FILTER_VALIDATE_EMAIL)
                ) {
                    if( Player::where('email', '=', $theRequests['alternate-' . Numbers::toWord($i) . '-email'])->exists()){
                        $player = Player::where('email', '=', $theRequests['alternate-' . Numbers::toWord($i) . '-email'])->first();
                    } else {
                        $player = new Player();
                        if($request->has('alternate-' . Numbers::toWord($i) . '-username') and $request->input('alternate-' . Numbers::toWord($i) . '-username')!='' and $request->input('alternate-' . Numbers::toWord($i) . '-username')!=null) {
                            $player->username = $request->input('alternate-' . Numbers::toWord($i) . '-username');
                        }else{
                            $username = $this->createRandomNameOrUsername();
                            $player->username = $username;
                        }
                        $player->email = $request->input('alternate-' . Numbers::toWord($i) . '-email');
                        $player->save();
                    }
                    // attach player to team/tournament/game
                    $player::createRelation([
                        'player' => $player,
                        'tournament' => $tournament,
                        'game' => $tournament->game,
                        'team' => $team,
                    ]);
                }
            }
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->error($ex->getMessage());
        }


        return $next($request);
    }
    
    private function error($return){
        return response(['error' => [$return]],200);
    }

    /**
     * @return string
     */
    private function createRandomNameOrUsername()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $username = '';
        for ($i = 0; $i < 12; $i++) {
            $username .= $characters[rand(0, $charactersLength - 1)];
        }
        return $username;
    }
}
