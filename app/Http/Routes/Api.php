<?php
use App\Helpers\Backend\Manage\Api\Filter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

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
    Route::get('/api/manage/score/all', ['as' => 'api.manage.score.all', function () use ($filterFields, $types) {
        $model = '\\App\\Models\\Championship\\Score';
        try {
            $get = Filter::filterMultiDimension(
                $model::all()->toArray(),
                $filterFields,
                $types
            );
            return $get;
        } catch (\Exception $ex) {
            return Response::json(['error' => ['Could not find any scores.']], 400);
        }
    }]);

    /**
     * get scores by column
     */
    Route::get('/api/manage/score/{type}/{id}', ['as' => 'api.manage.score.find', function ($type, $typeId) use ($filterFields, $types) {
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
            return Response::json($get, 200);
        } catch (\Exception $ex) {
            return Response::json(['error' => ['Could not find score for ' . $type . ' with an id of ' . $typeId]], 400);
        }
    }]);

    /**
     * Get all of a type
     */
    Route::get('/api/manage/{type}/all', ['as' => 'api.manage.type.all',  function ($type) use ($filterFields) {
        $model = '\\App\\Models\\Championship\\' . ucfirst($type);

        if (!class_exists($model)) {
            return Response::json(['error' => ['Model ' . $model . ' does not exist.']], 400);
        }
        $get = Filter::filterSingleDimension($model::all()->toArray(), $filterFields);
        return Response::json($get, 200);
    }]);

    /**
     * Find one of type, either by id if id is numeric, or by name column
     */
    Route::get('/api/manage/{type}/find/{id}', ['as' => 'api.manage.type.find', function ($type, $typeId) use ($filterFields, $types) {
        $model = '\\App\\Models\\Championship\\' . ucfirst($type);

        if (!class_exists($model)) {
            return ['error' => ['Model ' . $model . ' does not exist.']];
        }
        try {
            // if typeId is a csv of ids then do a wherein call
            if(strpos($typeId, ',') !== false) {
                $get = [];
                $ids = explode(',', $typeId);
                $getIn = $model::whereIn('id', $ids)->get()->toArray();
                foreach($getIn as $in) {
                    array_push($get, Filter::filterSingleDimension($in, $filterFields));
                }
            }
            // if typeId is numeric then do a find
            elseif (is_numeric($typeId)) {
                $get = Filter::filterSingleDimension($model::find($typeId)->toArray(), $filterFields);
            // otherwise get the typeId by the name field
            } else {
                $get = Filter::filterSingleDimension($model::where('name', $typeId)->first()->toArray(), $filterFields);
            }
            return Response::json($get, 200);
        } catch (\Exception $ex) {
            return Response::json(['error' => ['Could not find ' . $type . ' with id or name of ' . $typeId]], 400);
        }

    }]);
});
