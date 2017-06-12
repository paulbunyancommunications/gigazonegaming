<?php

namespace App\Http\Middleware;

use App\Http\Requests\Request;
use App\Models\Championship\Relation\PlayerRelation;
use Closure;
use Exception;
use App\Http\Requests\LolTeamSignUpRequest;
use App\Models\Championship\Player;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Pbc\Bandolier\Type\Numbers;

class LolTeamSignUpMiddleware
{
    protected $tournament;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $rules = new LolTeamSignUpRequest;
        $theRequests = $request->all();
        $validator = Validator::make($theRequests, $rules->rules(), $rules->messages());
        if ($validator->fails()) {
            return Response::json(['error' => $validator->errors()->all()]);
        }
        // set tournament from request
        if (!$this->getTournament()) {
            $this->setTournament($request->input('tournament'));
        }
        // get tournament by name
        $tournament = Tournament::where('name', $this->getTournament())->first();
        $teams = $tournament->Teams()->get();
        $playerExist = [];
        $usernameExists = [];
        $j = 10;
        if (isset($theRequests['email']) AND Player::where('email', '=', $theRequests['email'])->exists()) {
            $id = Player::where('email', '=', $theRequests['email'])->first()->id;
            $email =  $theRequests['email'];
            $playerExist[$email] =  $id;
        }
        if (isset($theRequests['team-captain-lol-summoner-name']) AND Player::where('username', '=', $theRequests['team-captain-lol-summoner-name'])->exists()) {
            $email = Player::where('username', '=', $theRequests['team-captain-lol-summoner-name'])->first()->email;
            $username =  $theRequests['team-captain-lol-summoner-name'];
            $usernameExists[$email] =  $username;
        } //if not don't even push it to the array so we run faster through each.
        for ($i = 0; $i <= $j; $i++) {
            if (isset($theRequests['teammate-'.Numbers::toWord($i).'-email-address']) AND Player::where('email', '=', $theRequests['teammate-' . Numbers::toWord($i) . '-email-address'])->exists()) {
                $id = Player::where('email', '=', $theRequests['teammate-' . Numbers::toWord($i) . '-email-address'])->first()->id;
                $email = $theRequests['teammate-'.Numbers::toWord($i).'-email-address'];
                $playerExist[$email] = $id;
            }//if not dont even push it to the array so we run faster through each
            if (isset($theRequests['alternate-' . Numbers::toWord($i) . '-email-address']) AND Player::where('email', '=', $theRequests['alternate-' . Numbers::toWord($i) . '-email-address'])->exists()) {
                $id =  Player::where('email', '=', $theRequests['alternate-' . Numbers::toWord($i) . '-email-address'])->first()->id;
                $email =  $theRequests['alternate-' . Numbers::toWord($i) . '-email-address'];
                $playerExist[$email] =  $id;
            }//if not dont even push it to the array so we run faster through each
            if (isset($theRequests['teammate-'.Numbers::toWord($i).'-lol-summoner-name']) AND Player::where('username', '=', $theRequests['teammate-' . Numbers::toWord($i) . '-lol-summoner-name'])->exists()) {
                $email = Player::where('username', '=', $theRequests['teammate-' . Numbers::toWord($i) . '-lol-summoner-name'])->first()->email;
                $username = $theRequests['teammate-'.Numbers::toWord($i).'-lol-summoner-name'];
                $usernameExists[$email] = $username;
            }//if not dont even push it to the array so we run faster through each
            if (isset($theRequests['alternate-' . Numbers::toWord($i) . '-summoner-name']) AND Player::where('username', '=', $theRequests['alternate-' . Numbers::toWord($i) . '-summoner-name'])->exists()) {
                $email =  Player::where('username', '=', $theRequests['alternate-' . Numbers::toWord($i) . '-summoner-name'])->first()->email;
                $username =  $theRequests['alternate-' . Numbers::toWord($i) . '-summoner-name'];
                $usernameExists[$email] =  $username;
            }//if not dont even push it to the array so we run faster through each
        }
        $repeatedPlayers = [];
        if(count($playerExist) > 0) { //here we will check if the player already has a team in the same tournament in which they are signing in.
            foreach ($teams as $team) {
                foreach ($playerExist as $email => $p_id) {
                    $relation = PlayerRelation::where([
                        ['relation_type', "=", Team::class],
                        ['relation_id', "=", $team->id],
                        ['player_id', '=', $p_id]
                    ])->exists();
                    if($relation){
                        $repeatedPlayers[] = $email;
                    }
                }
            }
        }
        $error = [];
        if ($tournament === null) {
            $error[]= trans('tournament.not_found', ['tournament' => $this->getTournament()]);
        }
        if (count($repeatedPlayers)>0) {
            $error[]= trans('email.unique', $repeatedPlayers);
            $error[]= trans('your-lol-summoner-name.unique', $repeatedPlayers);
        }
        if (count($error)!=0) {
            return Response::json([
                'error' => $error
            ]);
        }
        try {
            DB::beginTransaction();
            // make new team
            $team = new Team();
            $team->tournament_id = $tournament->id;
            $team->name =  $request->input('team-name');
            $team->save();

            // check if captain already exists, if they do then return message about logging in to update their settings
            //todo: I already check for this. Delete it?
//                $findCaptain = Player::where('email', '=', $request->input('email'))->first();
//                if ($findCaptain) {
//                    return Response::json([
//                        'warning' => [
//                            trans('team.player_already_exists', ['email' => $request->input('email')])
//                        ]
//                    ]);
//                }

            // add captain
            if( Player::where('email', '=', $theRequests['email'])->exists()){
                $captain = Player::where('email', '=', $theRequests['email'])->first();
            } else {
                $captain = new Player();
                $captain->setAttribute('username', $request->input('team-captain-lol-summoner-name'));
                $captain->setAttribute('email', $request->input('email'));
                $captain->setAttribute('name', $request->input('name'));
                $captain->setAttribute('phone', $request->input('team-captain-phone'));
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
                if ($request->input('teammate-' . Numbers::toWord($i) . '-lol-summoner-name')
                    && filter_var($request->input('teammate-' . Numbers::toWord($i) . '-email-address'), FILTER_VALIDATE_EMAIL)
                ) {
                    if( Player::where('email', '=', $theRequests['teammate-' . Numbers::toWord($i) . '-email-address'])->exists()){
                        $player = Player::where('email', '=', $theRequests['teammate-' . Numbers::toWord($i) . '-email-address'])->first();
                    } else {
                        $player = new Player();
                        $player->username = $request->input('teammate-' . Numbers::toWord($i) . '-lol-summoner-name');
                        $player->email = $request->input('teammate-' . Numbers::toWord($i) . '-email-address');
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
            // add other players alternate-###-email-address alternate-###-summoner-name
            for ($i = 1; $i < $tournament->max_players; $i++) {
                if ($request->input('alternate-' . Numbers::toWord($i) . '-summoner-name')
                    && filter_var($request->input('alternate-' . Numbers::toWord($i) . '-email-address'), FILTER_VALIDATE_EMAIL)
                ) {
                    if( Player::where('email', '=', $theRequests['alternate-' . Numbers::toWord($i) . '-email-address'])->exists()){
                        $player = Player::where('email', '=', $theRequests['alternate-' . Numbers::toWord($i) . '-email-address'])->first();
                    } else {
                        $player = new Player();
                        $player->username = $request->input('alternate-' . Numbers::toWord($i) . '-summoner-name');
                        $player->email = $request->input('alternate-' . Numbers::toWord($i) . '-email-address');
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
        } catch (Exception $ex) {
            DB::rollBack();
            return Response::json(['error' => [$ex->getMessage()]]);
        }
        DB::commit();
        return $next($request);
    }

    /**
     * @param mixed $tournament
     * @return LolTeamSignUpMiddleware
     */
    public function setTournament($tournament)
    {
        $this->tournament = $tournament;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTournament()
    {
        return $this->tournament;
    }
}
