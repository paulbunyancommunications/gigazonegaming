<?php

Route::group(['middleware' => 'web', 'prefix' => 'auth', 'namespace' => 'App\Http\Controllers\Auth'], function()
{
	Route::get('/', 'AuthController@index');
});

Route::group(['middleware' => 'guest'], function () {

    Route::any('/auth', function () {
        return redirect('/auth/login')->with('warning', trans('auth.must_login'));
    });
    Route::get('/auth/login', ['as' => 'login', 'uses' => 'Auth\AuthController@login']);
    Route::post('/auth/login', ['as' => 'authenticate', 'uses' => 'Auth\AuthController@authenticate']);
    Route::get('/auth/create', ['as' => 'create', 'uses' => 'Auth\AuthController@create']);
    Route::post('/auth/create', ['as' => 'generate', 'uses' => 'Auth\AuthController@generate']);
    Route::get('/auth/recover', 'Auth\PasswordController@recover');
    Route::post('/auth/recover', 'Auth\PasswordController@sendRecoverCode');
    Route::get('/auth/reset', 'Auth\PasswordController@reset');
    Route::put('/auth/reset/{user}', 'Auth\PasswordController@resetPassword');
});