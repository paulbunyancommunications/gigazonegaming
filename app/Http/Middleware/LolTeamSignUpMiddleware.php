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
        
        // if tournament is not already set then get it from the request
        if (!$this->getTournament()) {
            $this->setTournament($request->input('tournament'));
        }
        // get tournament by name
        $tournament = Tournament::where('name', $this->getTournament())->get();
        if ($tournament->isEmpty()) {
            return \Response::json(['error' => ['Could not find tournament "' . $this->getTournament() . '"']]);
        }
        // make new team
        $team = new Team();
        $team->setAttribute('tournament_id', $tournament->first()->id);
        $team->setAttribute('name', $request->input('team-name'));
        $team->save();

        // add captain
        $captain = new Player();
        $captain->setAttribute('username', $request->input('team-captain-lol-summoner-name'));
        $captain->setAttribute('email', $request->input('email'));
        $captain->setAttribute('name', $request->input('name'));
        $captain->setAttribute('phone', $request->input('team-captain-phone'));
        $captain->setAttribute('team_id', $team->id);
        $captain->save();

        // add captain to team
        $team->captain = $captain->id;
        $team->save();

        // add other players
        for ($i = 1; $i <= config('championship.players.league-of-legends', 5); $i++) {
            if ($request->input('teammate-' . Numbers::toWord($i) . '-lol-summoner-name')) {
                $player = new Player();
                $player->setAttribute(
                    'username',
                    $request->input('teammate-' . Numbers::toWord($i) . '-lol-summoner-name')
                );
                $player->setAttribute(
                    'email',
                    $request->input('teammate-' . Numbers::toWord($i) . '-email-address')
                );
                $player->setAttribute('team_id', $team->id);
                $player->save();
            }
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
