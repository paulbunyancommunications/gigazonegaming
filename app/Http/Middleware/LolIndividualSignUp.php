<?php

namespace App\Http\Middleware;

use App\Http\Requests\LolIndividualSignUpRequest;
use App\Models\Championship\Game;
use App\Models\Championship\IndividualPlayer;
use Closure;

class LolIndividualSignUp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /**
         * Validate request, if fails return an error
         */
        $rules = new LolIndividualSignUpRequest();
        $validator = \Validator::make($request->all(), $rules->rules(), $rules->messages());
        if ($validator->fails()) {
            return \Response::json(['error' => $validator->errors()->all()]);
        }

        /**
         * Try and get the game league-of-legends. If missing return error.
         */
        $game = Game::byName('league-of-legends');

        if (!$game) {
            return \Response::json(['error' => ['Could not find game "League of Legends"']]);
        }
        /**
         * Make new individual team
         */
        $individual = new IndividualPlayer();
        $individual->game_id = $game->id;
        $individual->username = $request->input('your-lol-summoner-name');
        $individual->email = $request->input('email');
        $individual->phone = $request->input('your-phone');

        $individual->save();

        return $next($request);
    }
}
