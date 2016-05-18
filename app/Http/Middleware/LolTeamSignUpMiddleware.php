<?php

namespace App\Http\Middleware;

use App\Http\Requests\LolTeamSignUpRequest;
use App\Models\Championship\Player;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Pbc\Bandolier\Type\Numbers;
use Closure;

class LolTeamSignUpMiddleware
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
        $rules = new LolTeamSignUpRequest;
        $validator = \Validator::make($request->all(), $rules->rules(), $rules->messages());
        //dd($request->all());
        if ($validator->fails()) {
            return \Response::json(['error' => $validator->errors()->all()]);
        }

        // get tournament by name
        $tournament = Tournament::where('name',$request->input('tournament'))->first();

        // make new team
        $team = new Team();
        $team->setAttribute('tournament_id', $tournament->id);
        $team->setAttribute('name', $request->input('team-name'));
        $team->save();

        // add captain
        $captain = new Player();
        $captain->setAttribute('username', $request->input('team-captain-lol-summoner-name'));
        $captain->setAttribute('email', $request->input('email'));
        $captain->setAttribute('name', $request->input('name'));
        $captain->setAttribute('phone', $request->input('team-captain-phone'));
        $captain->setAttribute('captain', true);
        $captain->setAttribute('team_id', $team->id);
        $captain->save();

        // add other players
        for ($i = 1; $i <= 4; $i++) {
            $player = new Player();
            $player->setAttribute('username', $request->input('teammate-'.Numbers::toWord($i).'-lol-summoner-name'));
            $player->setAttribute('email', $request->input('teammate-'.Numbers::toWord($i).'-email-address'));
            $player->setAttribute('captain', false);
            $player->setAttribute('team_id', $team->id);
            $player->save();
        }
 
        return $next($request);
    }
}
