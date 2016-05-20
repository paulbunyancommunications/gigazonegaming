<?php
/**
 * Created by PhpStorm.
 * User: nelson_castillo
 * Date: 5/19/16
 * Time: 4:44 PM
 */

Route::get('/app/game', ['as' => 'game.index', 'uses' => 'Backend\Manage\GamesController@index']); //no need for middleware as we return the names only
Route::group(['middleware' => ['WPAdmin']], function () {
    Route::get('/app/game/edit/{id}', ['as' => 'game.edit', 'uses' => 'Backend\Manage\GamesController@edit']);
    Route::post('/app/game/edit/{id}', ['as' => 'game.editp', 'uses' => 'Backend\Manage\GamesController@update']);
    Route::post('/app/game/new/', ['as' => 'game.new', 'uses' => 'Backend\Manage\GamesController@save']);
    Route::get('/app/game/delete/{id}', ['as' => 'game.delete', 'uses' => 'Backend\Manage\GamesController@update']);
});