<?php
Route::group(['middleware' => ['WPAdmin']], function () {
    Route::resource('manage/scores', '\\App\\Http\\Controllers\\Backend\\Manage\\ScoresController');
});