<?php
/**
 * Created by PhpStorm.
 * User: nelson_castillo
 * Date: 5/19/16
 * Time: 4:44 PM
 */

Route::group(['middleware' => ['WPAdmin']], function () {
    Route::get('/manage/individualPlayer', ['as' => 'manage.individualPlayer.index', 'uses' => 'Backend\Manage\IndividualPlayersController@index']);
    Route::post('/manage/individualPlayer', ['as' => 'manage.individualPlayer.change', 'uses' => 'Backend\Manage\IndividualPlayersController@change']);
});
