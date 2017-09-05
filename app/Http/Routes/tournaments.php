<?php
/**
 * Routes for tournament signups
 *
 * Created 9/3/17 10:43 AM
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 */

Route::group(['middleware' => ['UpdateRecipient', 'CCAddRecipient']], function () {

    // team sign up request
    Route::post(
        '/lol-team-sign-up',
        [
            'uses' => '\Pbc\FormMail\Http\Controllers\FormMailController@requestHandler',
            'as' => 'lol-team-sign-up',
            'middleware' => ['LolTeamSignUp']
        ]
    );

    // individual sign up request
    Route::post(
        '/lol-individual-sign-up',
        [
            'uses' => '\Pbc\FormMail\Http\Controllers\FormMailController@requestHandler',
            'as' => 'lol-individual-sign-up',
            'middleware' => ['LolIndividualSignUp']
        ]
    );

});
Route::group(['middleware' => ['TournamentSignUp', 'UpdateRecipient', 'CCAddRecipient']], function () {
    Route::post(
        '/gigazone-gaming-2017-overwatch-sign-up',
        [
            'uses' => '\Pbc\FormMail\Http\Controllers\FormMailController@requestHandler',
            'as' => 'gigazone-gaming-2017-overwatch',
        ]
    );

    Route::post(
        '/gigazone-gaming-2017-madden-nfl-18-sign-up',
        [
            'uses' => '\Pbc\FormMail\Http\Controllers\FormMailController@requestHandler',
            'as' => 'gigazone-gaming-2017-madden-nfl-18',
        ]
    );
});

