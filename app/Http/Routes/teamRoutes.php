<?php
/**
 * Created by PhpStorm.
 * User: nelson_castillo
 * Date: 5/19/16
 * Time: 4:44 PM
 */
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['WPAdmin']], function () {
    Route::get('/manage/team', ['as' => 'manage.team.index', 'uses' => 'Backend\Manage\TeamsController@index']);
    Route::post('/manage/team', ['as' => 'manage.team.filter', 'uses' => 'Backend\Manage\TeamsController@filter']);
    Route::get('/manage/team/edit/{team_id}', ['as' => 'manage.team.edit', 'uses' => 'Backend\Manage\TeamsController@edit']);
    Route::put('/manage/team/edit/{team_id}', ['as' => 'manage.team.edited', 'uses' => 'Backend\Manage\TeamsController@update']);
    Route::post('/manage/team/new/', ['as' => 'manage.team.new', 'uses' => 'Backend\Manage\TeamsController@store']);
    Route::delete('/manage/team/delete_soft/{team_id}', ['as' => 'manage.team.delete_soft', 'uses' => 'Backend\Manage\TeamsController@destroy_soft']);
    Route::delete('/manage/team/delete_hard/{team_id}', ['as' => 'manage.team.delete_hard', 'uses' => 'Backend\Manage\TeamsController@destroy_hard']);
});
