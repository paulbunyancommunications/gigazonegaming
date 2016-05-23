<?php
/**
 * Created by PhpStorm.
 * User: nelson_castillo
 * Date: 5/19/16
 * Time: 4:44 PM
 */

Route::get('/manage/game', ['as' => 'manage.game.index', 'uses' => 'Backend\Manage\GamesController@index']); //no need for middleware as we return the names only
Route::group(['middleware' => ['WPAdmin']], function () {
    Route::get('/manage/game/edit/{game_id}', ['as' => 'manage.game.edit', 'uses' => 'Backend\Manage\GamesController@edit']);
    Route::put('/manage/game/edit/{game_id}', ['as' => 'manage.game.editp', 'uses' => 'Backend\Manage\GamesController@update']);
    Route::post('/manage/game/new/', ['as' => 'manage.game.new', 'uses' => 'Backend\Manage\GamesController@save']);
    Route::get('/manage/game/delete/{game_id}', ['as' => 'manage.game.delete', 'uses' => 'Backend\Manage\GamesController@destroy']);
});