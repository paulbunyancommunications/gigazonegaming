<?php
Route::group(['middleware' => ['WPAdmin']], function () {
    Route::post('/manage/search', ['as' => 'manage.search', 'uses' => 'Backend\Manage\SearchController@doSearch']);
});