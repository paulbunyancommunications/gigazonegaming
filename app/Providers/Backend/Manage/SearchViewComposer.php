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
            $doSearch = $this->doSearch($request);
            $view->with('search', $doSearch)
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
        $searchTerm = $request::get('search');
        $modelsBasePath = app_path() . "/Models/Championship";
        $modelPaths = getModels($modelsBasePath);
        /** @var Array $search Output of search */
        $search = [];
        foreach ($modelPaths as $model) {
            $model = "App" . str_replace([app_path(), "app", "/"], ['', 'App', '\\'], $model);
            $modelName = explode('\\', $model);
            $modelName = array_pop($modelName);
            switch ($modelName) {
                case('Game'):
                case('Player'):
                case('Team'):
                case('Tournament'):
                $search[$modelName] = [];
                    $table = with(new $model)->getTable();
                    $columns = \Schema::connection($this->connection)->getColumnListing($table);
                    foreach ($columns as $column) {
                        $resultOfSearch = $model::where([[$column, '!=', ''], [$column, 'like', '%' . $searchTerm . '%']]);
                        if(count($resultOfSearch->get())) {
                            foreach($resultOfSearch->get() as $get) {
                                $search[$modelName][$get->id] = $get;
                            }
                        }
                    }
                    break;
            }
        }

        return $search;
    }
}


