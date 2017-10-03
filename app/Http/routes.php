<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Pbc\Bandolier\Type\Paths;

Route::get('/', function () {
    $path = Paths::curlPath(parse_url(Config::get('app.url'), PHP_URL_PATH)); // env('APP_URL', 'http://example.local');
    try {
        return Cache::remember('front-page-from-wp', 2, function () use ($path) {
            return Paths::fileGetContents(['toPath' => $path, 'clientParams' => ['verify' => false]]);
        });
        // @codeCoverageIgnoreStart
    } catch (\Illuminate\Database\QueryException $ex) {
        Log::error($ex->getMessage());
        return Paths::fileGetContents(['toPath' => $path, 'clientParams' => ['verify' => false]]);
        // @codeCoverageIgnoreEnd
    }
});


// get csrf token for forms outside app
Route::get('/frontend/session/csrf', ['as' => 'get_csrf', 'uses' => 'Frontend\SessionController@csrfToken']);

// update signup route
Route::post('/updates/signup', [
    'as' => 'updates.store',
    'uses' => 'Frontend\Updates\UpdatesController@store',
    'middleware' => ['CCAddRecipient']
]);

// Update route can come from /update-sign-up also
Route::post('/update-sign-up', [
    'uses' => 'Frontend\Updates\UpdatesController@store',
    'as' => 'update-sign-up',
    'middleware' => ['CCAddRecipient']
]);

Route::get('/updates/map', ['as' => 'updates.map', 'uses' => 'Frontend\Updates\UpdatesController@map']);

/**
 * Route group using UpdateRecipient
 * and CCAddRecipient middleware
 */
Route::group(['middleware' => ['UpdateRecipient', 'CCAddRecipient']], function () {

    // contact us form
    Route::post(
        '/contact-us',
        [
            'uses' => '\Pbc\FormMail\Http\Controllers\FormMailController@requestHandler',
            'as' => 'contact-us',
            'middleware' => []
        ]
    );
});
//Route::group(['middleware' => ['WPAdmin']], function () {
//    Route::get('/manage/game', ['as' => 'manage.game.index', 'uses' => 'Backend\Manage\GamesController@index']);
//});

/**
 * For Codeception Coverage
 */
// @codeCoverageIgnoreStart
Route::get('/report/{extra}', function () {

})->where('extra', '.*');

Route::get('/c3/{extra}', function () {
    require base_path('c3.php');
})->where('extra', '.*');

// @codeCoverageIgnoreEnd


foreach (File::allFiles(__DIR__ . '/Routes') as $partials) {
    require_once $partials->getPathname();
}



/**
 * Roman and Simon
 * Routes - LOL
 *
 */
Route::group(['middleware' => ['WPAdmin']], function () {
// Admin Page
    Route::get('/gamedisplay/admin','GameDisplay\AdminPageController@startGameDisplay');
    Route::get('/GameDisplay/cache','GameDisplay\SimonCacheController@submitCache');
    Route::get('/GameDisplay/cacheChampions','GameDisplay\SimonCacheController@getChampions');
    Route::get('/GameDisplay/clear','GameDisplay\SimonCacheController@clearCache');
// Champion Override Page
    Route::get('/gamedisplay/override','GameDisplay\ChampionOverrideController@pageLoad');
    Route::get('/GameDisplay/championsOverride','GameDisplay\SimonCacheController@cacheChampionOverride');
});

// Team View Display
Route::get('/gamedisplay/{team}','GameDisplay\GameDisplayController@teamViewDisplay')->where('team', 'team1|team2');
Route::get('/GameDisplay/getData','GameDisplay\GameDisplayController@getData');
Route::get('/GameDisplay/Update','GameDisplay\GameDisplayController@updateData');
Route::get('/GameDisplay/CarouselUpdate','GameDisplay\GameDisplayController@carouselUpdate');

// Customer Page todo: Change name to "User" not Customer
Route::get('/GameDisplay/getTeamName', function () {
    $teamNames = array();
    if (Cache::has('Team1Name') && Cache::has('Team2Name') && Cache::has('Team1Color') && Cache::has('Team2Color')) {
        array_push($teamNames, Cache::get('Team1Name'));
        array_push($teamNames, Cache::get('Team2Name'));
        array_push($teamNames, Cache::get('Team1Color'));
        array_push($teamNames, Cache::get('Team2Color'));
        return response()->json($teamNames);
    }
    return response()->json(false);
});
Route::get('/gamedisplay', function (){return view('/LeagueOfLegends/customerPage');});

Route::get('/player/login',function(){
    return view('/playerUpdate/login')->withEmail("")->with('success',"");
});
Route::post('/player/login','Auth\PlayerUpdateController@postLogin');

Route::get('/player/register',function(){
    return view('/playerUpdate/register')->with('success',"");
});
Route::post('/player/register','Auth\PlayerUpdateController@postRegister');
Route::get('/player/playerUpdate','Auth\PlayerUpdateController@playerUpdate');
Route::post('/player/playerUpdate','Auth\PlayerUpdateController@postUpdate');
Route::get('/player/logout','Auth\PlayerUpdateController@logout');
Route::post('/player/logout','Auth\PlayerUpdateController@postLogin');
Route::get('/player/recover',function (){
    return view('/playerUpdate/recover');
});

Route::post('/player/recover','Auth\PlayerUpdateController@postRecover');
Route::get('/player/createPassword/{token}',function (){
    return view('/playerUpdate/createPassword')->with('success','');
});

Route::post('/player/createPassword/{token}','Auth\PlayerUpdateController@createPassword');