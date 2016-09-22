<?php
/**
 * Created by PhpStorm.
 * User: nelson_castillo
 * Date: 5/19/16
 * Time: 4:44 PM
 */

Route::group(['middleware' => ['WPAdmin']], function () {
    Route::get('/manage/game', ['as' => 'manage.game.index', 'uses' => 'Backend\Manage\GamesController@index']);
    Route::get('/manage/game/edit/{game_id}', ['as' => 'manage.game.edit', 'uses' => 'Backend\Manage\GamesController@create']);
    Route::put('/manage/game/edit/{game_id}', ['as' => 'manage.game.edited', 'uses' => 'Backend\Manage\GamesController@update']);
    Route::post('/manage/game/new/', ['as' => 'manage.game.new', 'uses' => 'Backend\Manage\GamesController@store']);
    Route::delete('/manage/game/delete/{game_id}', ['as' => 'manage.game.delete', 'uses' => 'Backend\Manage\GamesController@destroy']);
});
