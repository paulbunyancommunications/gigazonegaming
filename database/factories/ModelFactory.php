<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/


$factory->define(App\Models\UpdateRecipients::class, function (Faker\Generator $faker) {

    return [
        'email' => $faker->email,
        'participate' => $faker->boolean(),
        'geo_lat' => $faker->latitude,
        'geo_long' => $faker->longitude,
    ];
});

$factory->define(App\Models\Championship\Game::class, function (Faker\Generator $faker) {

    return [
        'name' => implode('-', $faker->words()),
        'title' => $faker->sentence(),
        'description' => $faker->paragraph(),
        'uri' => $faker->url(),
    ];
});

$factory->define(App\Models\Championship\Team::class, function (Faker\Generator $faker) {

    return [
        'name' => implode('-', $faker->words()),
        'emblem' => $faker->imageUrl(),
        'tournament_id' => factory(App\Models\Championship\Tournament::class)->create([])->id,
        'captain' => 0,

    ];
});

$factory->define(App\Models\Championship\Player::class, function (Faker\Generator $faker) {

    return [
        'username' => $faker->userName,
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'team_id' => factory(App\Models\Championship\Team::class)->create([])->id,
    ];
});

$factory->define(App\Models\Championship\IndividualPlayer::class, function (Faker\Generator $faker) {

    return [
        'username' => $faker->userName,
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'game_id' => factory(App\Models\Championship\Game::class)->create([])->id,
    ];
});

$factory->define(App\Models\Championship\Tournament::class, function (Faker\Generator $faker) {

    return [
        'name' => implode('-', $faker->words(4)),
        'game_id' => factory(App\Models\Championship\Game::class)->create([])->id,
    ];
});

$factory->define(App\Models\WpUser::class, function (Faker\Generator $faker) {

    return [
        'user_login' => $faker->userName,
        'user_pass' => md5($faker->password()),
        'user_nicename' => $faker->name,
        'user_email' => $faker->email,
        'display_name' => $faker->name,
    ];
});
