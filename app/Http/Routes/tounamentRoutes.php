<?php
/**
 * Created by PhpStorm.
 * User: nelson_castillo
 * Date: 5/19/16
 * Time: 4:44 PM
 */

Route::group(['middleware' => ['WPAdmin']], function () {
    Route::get('/manage/tournament', ['as' => 'manage.tournament.index', 'uses' => 'Backend\Manage\TournamentsController@index']);
    Route::post('/manage/tournament', ['as' => 'manage.tournament.filter', 'uses' => 'Backend\Manage\TournamentsController@filter']);
    Route::get('/manage/tournament/edit/{tournament_id}', ['as' => 'manage.tournament.edit', 'uses' => 'Backend\Manage\TournamentsController@edit']);
    Route::put('/manage/tournament/edit/{tournament_id}', ['as' => 'manage.tournament.edited', 'uses' => 'Backend\Manage\TournamentsController@update']);
    Route::post('/manage/tournament/new', ['as' => 'manage.tournament.new', 'uses' => 'Backend\Manage\TournamentsController@store']);
    Route::delete('/manage/tournament/delete/{tournament_id}', ['as' => 'manage.tournament.delete', 'uses' => 'Backend\Manage\TournamentsController@destroy']);
});
