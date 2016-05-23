<?php
Route::get('/api/game', ['as' => 'api.game.index', 'uses' => 'Api\Championship\GamesController@index']);
Route::get('/api/game/{game}', ['as' => 'api.game.get','uses' => 'Api\Championship\GamesController@getGame']);