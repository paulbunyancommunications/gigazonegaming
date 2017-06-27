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

Route::get('/', function () {
    $path = Config::get('app.url'); // env('APP_URL', 'http://example.local');
    try {
        return Cache::remember('front-page-from-wp', 2, function () use ($path) {
            return file_get_contents($path);
        });
        // @codeCoverageIgnoreStart
    } catch (\Illuminate\Database\QueryException $ex) {
        Log::error($ex->getMessage());
        return file_get_contents($path);
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

    // team sign up request
    Route::post(
        '/lol-team-sign-up',
        [
            'uses' => '\Pbc\FormMail\Http\Controllers\FormMailController@requestHandler',
            'as' => 'lol-team-sign-up',
            'middleware' => ['LolTeamSignUp']
        ]
    );

    // individual sign up request
    Route::post(
        '/lol-individual-sign-up',
        [
            'uses' => '\Pbc\FormMail\Http\Controllers\FormMailController@requestHandler',
            'as' => 'lol-individual-sign-up',
            'middleware' => ['LolIndividualSignUp']
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

Route::get('/GameDisplay/Admin','GameDisplay\AdminPageController@startGameDisplay');
Route::get('/GameDisplay/customer','GameDisplay\GameDisplayController@customerDisplay');
Route::get('/GameDisplay/override','GameDisplay\GameDisplayController@championOverride');
Route::get('/GameDisplay/team1','GameDisplay\GameDisplayController@team1ViewDisplay');
Route::get('/GameDisplay/team2','GameDisplay\GameDisplayController@team2ViewDisplay');
Route::get('/GameDisplay/ajax','GameDisplay\GameDisplayController@ajaxCheckRequest');
Route::get('/GameDisplay/cache','GameDisplay\SimonCacheController@SubmitCache');
Route::get('/GameDisplay/cacheChampions','GameDisplay\SimonCacheController@getChampions');
Route::get('/GameDisplay/getData','GameDisplay\GameDisplayController@getData');
Route::get('/GameDisplay/Update','GameDisplay\GameDisplayController@updateData');
Route::get('/GameDisplay/getTeamName','GameDisplay\GameDisplayController@getTeamName');
Route::get('/GameDisplay/championsOverride','GameDisplay\GameDisplayController@cacheChampionOverride');
Route::get('/GameDisplay/clear','GameDisplay\GameDisplayController@clearCache');


