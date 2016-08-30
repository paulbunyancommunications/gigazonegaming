<?php

# Standard User Routes
Route::group(['middleware' => ['auth']], function()
{
    Route::get('/auth/logout', ['as' => 'logout', 'uses' => 'Frontend\Auth\AuthController@logout']);
    Route::get('/my_settings', ['as' => 'my_settings', 'uses' => 'Backend\MySettingsController@index']);
    Route::get('/my_settings/password', ['as' => 'my_settings.password','uses' => 'Backend\MySettingsController@password']);
    Route::put('/my_settings/password', ['as' => 'my_settings.update_password','uses' => 'Backend\MySettingsController@updatePassword']);
    Route::get('/my_settings/details', ['as' => 'my_settings.details', 'uses' => 'Backend\MySettingsController@userDetails']);
    Route::put('/my_settings/details', ['as' => 'my_settings.update_details', 'uses' => 'Backend\MySettingsController@updateUserDetails']);
});