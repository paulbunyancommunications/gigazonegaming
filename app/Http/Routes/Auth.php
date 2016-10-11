<?php

Route::group(['middleware' => 'web', 'prefix' => 'auth', 'namespace' => 'Auth'], function()
{
	Route::get('/', 'AuthController@index');
});

Route::group(['middleware' => 'guest', 'namespace' => 'Auth'], function () {

    Route::any('/auth', function () {
        return redirect('/auth/login')->with('warning', trans('auth.must_login'));
    });
    Route::get('/auth/login', ['as' => 'login', 'uses' => 'AuthController@login']);
    Route::post('/auth/login', ['as' => 'authenticate', 'uses' => 'AuthController@authenticate']);
    Route::get('/auth/create', ['as' => 'create', 'uses' => 'AuthController@create']);
    Route::post('/auth/create', ['as' => 'generate', 'uses' => 'AuthController@generate']);
    Route::get('/auth/recover', 'PasswordController@recover');
    Route::post('/auth/recover', 'PasswordController@sendRecoverCode');
    Route::get('/auth/reset', 'PasswordController@reset');
    Route::put('/auth/reset/{user}', 'PasswordController@resetPassword');
});