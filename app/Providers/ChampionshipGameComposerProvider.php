<?php

namespace App\Providers;

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
            $view->with('games', Game::all()->toArray());
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
