<?php
/**
 * Created by PhpStorm.
 * User: nelson_castillo
 * Date: 10/13/16
 * Time: 8:44 AM
 */


Route::group(['middleware' => ['WPAdmin']], function () {
    Route::get('/manage/scoreboard/', ['as' => 'manage.game.bracket', 'uses' => 'Backend\Manage\BracketController@index']);
    Route::get('/manage/scoreboard/getTournament', ['as' => 'manage.game.bracket.t', 'uses' => 'Backend\Manage\BracketController@getTournament']);
    Route::get('/manage/scoreboard/getTournaments', ['as' => 'manage.game.bracket.ts', 'uses' => 'Backend\Manage\BracketController@getTournaments']);
    Route::post('/manage/scoreboard/save', ['as' => 'manage.game.bracket.s', 'uses' => 'Backend\Manage\BracketController@save']);
});
