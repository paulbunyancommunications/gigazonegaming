<?php

namespace App\Providers;

use App\Models\Championship\Player;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use App\Models\Championship\Game;
use \View;

class ChampionshipGameComposerProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['game.game'], function ($view) {
            $view->with('games', Game::orderBy('name')->get()->toArray());
        });
        View::composer(['game.tournament'], function ($view) {
            $view->with('games', Game::orderBy('name')->get()->toArray())
                ->with('tournaments', Tournament::orderBy('name')->get()->toArray());
        });
        View::composer(['game.team'], function ($view) {
            $view->with('games', Game::orderBy('name')->get()->toArray())
                ->with('tournaments', Tournament::orderBy('name')->get()->toArray())
                ->with('count', Player::select(DB::raw("COUNT(id) as team_count"), "team_id")->groupBy('team_id')->get()->toArray())
                ->with('teams', Team::orderBy('name')->get()->toArray());
        });
        View::composer(['game.player'], function ($view) {
            $view->with('games', Game::orderBy('name')->get()->toArray())
                ->with('tournaments', Tournament::orderBy('name')->get()->toArray())
                ->with('teams', Team::all()->toArray())
                ->with('players', Player::orderBy('team_id')->get()->toArray());
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
