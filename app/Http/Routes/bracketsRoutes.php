<?php
/**
 * Created by PhpStorm.
 * User: nelson_castillo
 * Date: 10/13/16
 * Time: 8:44 AM
 */


Route::group(['middleware' => ['WPAdmin']], function () {
    Route::get('/manage/scoreboard/', ['as' => 'manage.game.bracket', 'uses' => 'Backend\Manage\BracketController@index']);
});
