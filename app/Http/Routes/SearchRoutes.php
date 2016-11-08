<?php
Route::group(['middleware' => ['WPAdmin']], function () {
    Route::any('/manage/search', ['as' => 'manage.search', 'uses' => 'Backend\Manage\SearchController@doSearch']);
});