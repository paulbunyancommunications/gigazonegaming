<?php
/**
 * Created by PhpStorm.
 * User: nelson_castillo
 * Date: 5/19/16
 * Time: 4:44 PM
 */
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['WPAdmin']], function () {
    Route::get('/manage/individualPlayer', ['as' => 'manage.individualPlayer.index', 'uses' => 'Backend\Manage\IndividualPlayersController@index']);
    Route::post('/manage/individualPlayer', ['as' => 'manage.individualPlayer.change', 'uses' => 'Backend\Manage\IndividualPlayersController@change']);
    Route::get('/manage/teamMaker', ['as' => 'manage.individualPlayer.team_maker', 'uses' => 'Backend\Manage\IndividualPlayersController@teamMake']);
    Route::post('/manage/teamMaker', ['as' => 'manage.individualPlayer.team_save', 'uses' => 'Backend\Manage\IndividualPlayersController@teamCreate']);
    Route::put('/manage/teamMaker', ['as' => 'manage.individualPlayer.team_update', 'uses' => 'Backend\Manage\IndividualPlayersController@teamFill']);
});
