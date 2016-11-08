<?php

namespace App\Providers\Backend\Manage;

use Illuminate\Support\ServiceProvider;

class SearchViewComposer extends ServiceProvider
{
    protected $connection = 'mysql_champ';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        \View::composer(['game.partials.search'], function ($view) {
            $request = new \Request();
            $view->with('search', \ChampionshipSearch::all($request))
                ->with('searchTerm', $request::get('search'));
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

    /**
     * @param \Request $request
     * @return array
     */
    public function doSearch(\Request $request)
    {
        return \ChampionshipSearch::all($request);
    }
}


