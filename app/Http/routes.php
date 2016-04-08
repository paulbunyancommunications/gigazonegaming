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

Route::get('/', function () {
    $path = env('APP_URL', 'http://example.local');
    try {
        return \Cache::remember('front-page-from-wp', 2, function () use ($path) {
            return file_get_contents($path);
        });
        // @codeCoverageIgnoreStart
    } catch (\Illuminate\Database\QueryException $ex) {
        \Log::error($ex->getMessage());
        return file_get_contents($path);
        // @codeCoverageIgnoreEnd
    }
});
// update signup route
Route::post('/updates/signup', ['as' => 'updates.store','uses' => 'Frontend\Updates\UpdatesController@store']);

/**
 * For Codeception Coverage
 */
// @codeCoverageIgnoreStart
Route::get('/report/{extra}', function () {
    require base_path('c3.php');
})->where('extra', '.*');
// @codeCoverageIgnoreEnd