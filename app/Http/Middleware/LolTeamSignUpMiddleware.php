<?php

namespace App\Http\Middleware;

use App\Http\Requests\LolTeamSignUpRequest;
use App\Models\Championship\Player;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Closure;
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
        $validator = \Validator::make($request->all(), $rules->rules(), $rules->messages());
        if ($validator->fails()) {
            return \Response::json(['error' => $validator->errors()->all()]);
        }

        // set tournament from request
        if (!$this->getTournament()) {
            $this->setTournament($request->input('tournament'));
        }
        // get tournament by name
        $tournament = Tournament::where('name', $this->getTournament())->first();
        if ($tournament === null) {
            return \Response::json([
                'error' => [
                    trans('tournament.not_found', ['tournament' => $this->getTournament()
                    ])
                ]
            ]);
        }
        try {
            \DB::transaction(function () use ($tournament, $request) {

                // make new team
                $team = new Team();
                $team->tournament_id = $tournament->id;
                $team->name =  $request->input('team-name');
                $team->save();

                // check if captain already exists, if they do then return message about logging in to update their settings
                $findCaptain = Player::where('email', '=', $request->input('email'))->first();
                if ($findCaptain) {
                    return \Response::json([
                        'warning' => [
                            trans('team.player_already_exists', ['email' => $request->input('email')])
                        ]
                    ]);
                }

                // add captain
                $captain = new Player();
                $captain->setAttribute('username', $request->input('team-captain-lol-summoner-name'));
                $captain->setAttribute('email', $request->input('email'));
                $captain->setAttribute('name', $request->input('name'));
                $captain->setAttribute('phone', $request->input('team-captain-phone'));
                $captain->save();

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
                        $player = new Player();
                        $player->username = $request->input('teammate-' . Numbers::toWord($i) . '-lol-summoner-name');
                        $player->email = $request->input('teammate-' . Numbers::toWord($i) . '-email-address');
                        $player->save();
                        // attach player to team/tournament/game
                        $player::createRelation([
                            'player' => $player,
                            'tournament' => $tournament,
                            'game' => $tournament->game,
                            'team' => $team,
                        ]);
                    }
                }
            });
        } catch (\Exception $ex) {
            return \Response::json(['error' => [$ex->getMessage()]]);
        }

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
