<?php
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['WPAdmin']], function () {
    Route::any('/manage/search', ['as' => 'manage.search', 'uses' => 'Backend\Manage\SearchController@doSearch']);
});