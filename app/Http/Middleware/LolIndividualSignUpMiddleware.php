<?php

namespace App\Http\Middleware;

use App\Http\Requests\LolIndividualSignUpRequest;
use App\Models\Championship\Game;
use App\Models\Championship\IndividualPlayer;
use Closure;

class LolIndividualSignUpMiddleware
{
    
    protected $game = 'league-of-legends';
    
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
        $game = Game::where('name', '=', $this->getGame())->get();

        if ($game->isEmpty()) {
            return \Response::json(['error' => ['Could not find game "'.$this->getGame().'"']]);
        }
        /**
         * Make new individual team
         */
        $individual = new IndividualPlayer();
        $individual->game_id = $game->first()->id;
        $individual->username = $request->input('your-lol-summoner-name');
        $individual->email = $request->input('email');
        $individual->phone = $request->input('your-phone');
        $individual->name = $request->input('name');

        $individual->save();

        return $next($request);
    }

    /**
     * @param string $game
     * @return LolIndividualSignUpMiddleware
     */
    public function setGame($game)
    {
        $this->game = $game;
        return $this;
    }

    /**
     * @return string
     */
    public function getGame()
    {
        return $this->game;
    }
}
