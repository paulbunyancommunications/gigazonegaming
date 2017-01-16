<?php
/**
 * Created by PhpStorm.
 * User: nelson_castillo
 * Date: 5/19/16
 * Time: 4:44 PM
 */
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['WPAdmin']], function () {
    Route::get('/manage/email/', ['as' => 'manage.email.form', 'uses' => 'Backend\Manage\EmailController@email']);
    Route::post('/manage/email/', ['as' => 'manage.email.send', 'uses' => 'Backend\Manage\EmailController@email_send']);
    Route::get('/manage/email/send/', ['as' => 'manage.email.get', 'uses' => 'Backend\Manage\EmailController@email_get']);
    Route::post('/manage/email/send/', ['as' => 'manage.email.post', 'uses' => 'Backend\Manage\EmailController@email_get']);
});
