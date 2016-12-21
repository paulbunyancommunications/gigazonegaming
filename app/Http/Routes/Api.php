<?php
use App\Helpers\Backend\Manage\Api\Filter;
use Illuminate\Support\Facades\Route;

/**
 * Api Routes
 */
Route::group(['middleware' => 'api', 'namespace' => 'Api\Championship'], function () {
    Route::get('/api/game', ['as' => 'api.game.index', 'uses' => 'GamesController@index']);
    Route::get('/api/game/{game}', ['as' => 'api.game.get', 'uses' => 'GamesController@show']);
    Route::get('/api/tournament_name/{name}',
        ['as' => 'api.from_tournament_name.get', 'uses' => 'GameTournamentTeamPlayerController@to_name']);
    Route::get('/api/tournament_id/{id}',
        ['as' => 'api.from_tournament_id.get', 'uses' => 'GameTournamentTeamPlayerController@to_id']);
    Route::get('/api/team_name/{name}',
        ['as' => 'api.from_team_name.get', 'uses' => 'GameTournamentTeamPlayerController@te_name']);
    Route::get('/api/team_id/{id}',
        ['as' => 'api.from_team_id.get', 'uses' => 'GameTournamentTeamPlayerController@te_id']);
});

Route::group(['middleware' => ['WPAdmin'], 'namespace' => 'Api\Championship'], function () {
    Route::get('/api/print/', ['as' => 'api.print.get', 'uses' => 'PrintingController@index']);
    Route::get('/api/print/game/{game_id}', ['as' => 'api.print.get', 'uses' => 'PrintingController@printGame']);
    Route::get('/api/print/tournament/{tournament_id}',
        ['as' => 'api.print.get', 'uses' => 'PrintingController@printTournament']);
    Route::get('/api/print/team/{team_id}', ['as' => 'api.print.get', 'uses' => 'PrintingController@printTeam']);
    Route::get('/api/print/player/{player_id}', ['as' => 'api.print.get', 'uses' => 'PrintingController@printPlayer']);

});

/**
 * Championship api routes
 */
Route::group(['middleware' => ['api']], function () {

    /** @var array $filterFields list of columns to filter out */
    $filterFields = ['email', 'phone', 'username', 'created_at', 'updated_at', 'updated_by', 'updated_on'];

    /** @var array $types list of model types */
    $types = ['player', 'tournament', 'team', 'game'];

    /**
     * get scores from backend
     */
    Route::get('/api/manage/score/all', function () use ($filterFields, $types) {
        $model = '\\App\\Models\\Championship\\Score';
        try {
            $get = Filter::filterMultiDimension(
                $model::all()->toArray(),
                $filterFields,
                $types
            );
            return $get;
        } catch (\Exception $ex) {
            return ['error' => 'Could not find any scores'];
        }
    });

    /**
     * get scores by column
     */
    Route::get('/api/manage/score/{type}/{id}', function ($type, $typeId) use ($filterFields, $types) {
        try {
            $model = '\\App\\Models\\Championship\\Score';
            if(strpos(',', $typeId) !== false) {
                $get = Filter::filterMultiDimension(
                    $model::whereIn($type, implode(',', $typeId))->get()->toArray(),
                    $filterFields,
                    $types
                );
            } else {
                $get = Filter::filterMultiDimension(
                    $model::where($type, '=', $typeId)->get()->toArray(),
                    $filterFields,
                    $types
                );
            }
            return $get;
        } catch (\Exception $ex) {
            return ['error' => 'Could not find score for ' . $type . ' with an id of ' . $typeId];
        }
    });

    /**
     * Get all of a type
     */
    Route::get('/api/manage/{type}/all', function ($type) use ($filterFields) {
        $model = '\\App\\Models\\Championship\\' . ucfirst($type);

        if (!class_exists($model)) {
            return ['error' => 'Model ' . $model . ' does not exist.'];
        }
        $get = Filter::filterSingleDimension($model::all()->toArray(), $filterFields);
        return $get;
    });

    /**
     * Find one of type, either by id if id is numeric, or by name column
     */
    Route::get('/api/manage/{type}/find/{id}', function ($type, $typeId) use ($filterFields, $types) {
        $model = '\\App\\Models\\Championship\\' . ucfirst($type);

        if (!class_exists($model)) {
            return ['error' => 'Model ' . $model . ' does not exist.'];
        }
        try {
            if (is_numeric($typeId)) {
                $get = Filter::filterSingleDimension($model::find($typeId)->toArray());
            } elseif (strpos(',', $typeId) !== false) {
                $get = Filter::filterMultiDimension(
                    $model::whereIn($type, implode(',', $typeId))->get()->toArray(),
                    $filterFields,
                    $types
                );
            } else {
                $get = Filter::filterSingleDimension($model::where('name', $typeId)->first()->toArray());
            }
            return $get;
        } catch (\Exception $ex) {
            return ['error' => 'Could not find ' . $type . ' with id or name of ' . $typeId];
        }

    });
});
