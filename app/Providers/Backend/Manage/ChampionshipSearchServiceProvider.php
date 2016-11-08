<?php

namespace App\Providers\Backend\Manage;

use Illuminate\Support\ServiceProvider;

class ChampionshipSearchServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ChampionshipSearch', 'App\Helpers\Backend\Manage\ChampionshipSearch');
    }
}
