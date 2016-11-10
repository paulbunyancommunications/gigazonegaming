<?php
namespace App\Helpers\Backend\Manage;

/**
 * Class ChampionshipSearch
 */
/**
 * Class ChampionshipSearch
 * @package App\Helpers\Backend\Manage
 */
class ChampionshipSearch
{

    /**
     * @var string
     */
    protected $connection = 'mysql_champ';

    /**
     * Get all by keyword
     *
     * @param \Request $request
     * @return array
     */
    public function all(\Request $request)
    {
        list($searchTerm, $modelPaths) = $this->prepSearch($request);
        $search = $this->searchModels($modelPaths, $searchTerm, ['Game', 'Player','Tournament','Team']);

        return $search;
    }

    /**
     * @param \Request $request
     * @return array
     */
    protected function prepSearch(\Request $request)
    {
        $searchTerm = $this->searchTerm($request);
        $modelPaths = $this->modelPaths();
        return array($searchTerm, $modelPaths);
    }

    /**
     * @param \Request $request
     * @return mixed
     */
    protected function searchTerm(\Request $request)
    {
        return $request::get('search');
    }

    /**
     * Get model paths
     *
     * @return array
     */
    protected function modelPaths()
    {
        return getModels($this->modelBasePath());
    }

    /**
     * Base path to championship models
     *
     * @return string
     */
    protected function modelBasePath()
    {
        return app_path() . "/Models/Championship";
    }

    /**
     * @param $modelPaths
     * @param $searchTerm
     * @param array $list
     * @return mixed
     */
    protected function searchModels($modelPaths, $searchTerm, array $list = [])
    {
        $search = [];
        foreach ($modelPaths as $model) {
            list($model, $modelName) = $this->prepModel($model);
            if (in_array($modelName, $list)) {
                $this->doSearch($search, $searchTerm, $modelName, $model);
            }
        }
        return $search;
    }

    /**
     * @param $model
     * @return array
     */
    protected function prepModel($model)
    {
        $model = $this->modelNameSpace($model);
        $modelName = $this->modelName($model);
        return array($model, $modelName);
    }

    /**
     * prepare model name string
     *
     * @param $model
     * @return string
     */
    protected function modelNameSpace($model)
    {
        return "App" . str_replace([app_path(), "app", "/"], ['', 'App', '\\'], $model);
    }

    /**
     * @param $model
     * @return mixed
     */
    protected function modelName($model)
    {
        $modelName = explode('\\', $model);
        return array_pop($modelName);
    }

    /**
     * @param $search
     * @param $searchTerm
     * @param $modelName
     * @param $model
     */
    protected function doSearch(&$search, $searchTerm, $modelName, $model)
    {
        $search[$modelName] = [];
        $table = with(new $model)->getTable();
        $columns = \Schema::connection($this->connection)->getColumnListing($table);
        foreach ($columns as $column) {
            $resultOfSearch = $model::where([[$column, '!=', ''], [$column, 'LIKE', '%' . $searchTerm . '%']]);
            if (count($resultOfSearch->get())) {
                foreach ($resultOfSearch->get() as $get) {
                    $search[$modelName][$get->id] = $get;
                }
            }
        }
    }

    /**
     * Get players by key word
     *
     * @param \Request $request
     * @return mixed
     */
    public function player(\Request $request)
    {
        list($searchTerm, $modelPaths) = $this->prepSearch($request);
        $search = $this->searchModels($modelPaths, $searchTerm, ['Player']);

        return $search;
    }

    /**
     * Search for game by keyword
     * @param \Request $request
     * @return mixed
     */
    public function game(\Request $request)
    {
        list($searchTerm, $modelPaths) = $this->prepSearch($request);
        $search = $this->searchModels($modelPaths, $searchTerm, ['Game']);

        return $search;
    }

    /**
     * Search for team by keyword
     */
    public function team(\Request $request)
    {
        list($searchTerm, $modelPaths) = $this->prepSearch($request);
        $search = $this->searchModels($modelPaths, $searchTerm, ['Team']);

        return $search;
    }

    /**
     * Search for tournament by keyword
     */
    public function tournament(\Request $request)
    {
        list($searchTerm, $modelPaths) = $this->prepSearch($request);
        $search = $this->searchModels($modelPaths, $searchTerm, ['Tournament']);

        return $search;
    }
}
