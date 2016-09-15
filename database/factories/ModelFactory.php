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

    $id = factory(App\Models\Auth\Users\User::class)->create([])->id;
    return [
        'username' => $faker->userName,
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'user_id' => $id,
    ];
});

//$factory->define(App\Models\Championship\IndividualPlayer::class, function (Faker\Generator $faker) {
//
//    return [
//        'username' => $faker->userName,
//        'email' => $faker->email,
//        'phone' => $faker->phoneNumber,
//        'game_id' => factory(App\Models\Championship\Game::class)->create([])->id,
//    ];
//});

$factory->define(App\Models\Championship\Tournament::class, function (Faker\Generator $faker) {

    return [
        'name' => implode('-', $faker->words(4)),
        'game_id' => factory(App\Models\Championship\Game::class)->create([])->id,
    ];
});
$factory->define(App\Models\WpPost::class, function (Faker\Generator $faker) {

    return [
        'post_author' => 1,
        'post_date' => $faker->date("Y-m-d H:i:s"),
        'post_date_gmt' => $faker->date("Y-m-d H:i:s"),
        'post_content' => $faker->paragraph(10),
        'post_title' => $faker->sentence,
        'post_excerpt' => $faker->paragraph,
        'post_status' => 'publish',
        'comment_status' => 'closed',
        'ping_status' => 'closed',
        'post_password' => '',
        'post_name' => implode('-', $faker->words()),
        'to_ping' => '',
        'pinged' => '',
        'post_modified' => $faker->date("Y-m-d H:i:s"),
        'post_modified_gmt' => $faker->date("Y-m-d H:i:s"),
        'post_content_filtered' => '',
        'post_parent' => '',
        'guid' => 'http://gigazonegaming.local/auto-draft/',
        'menu_order' => 0,
        'post_type' => 'post',
        'post_mime_type' => '',
        'comment_count' => ''
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

use Cocur\Slugify\Slugify;

$factory->define(App\Models\Auth\Users\User::class, function (Faker\Generator $faker) {
    return [
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'permissions' => [$faker->word => true, $faker->word => true],
        'last_login' => date("Y-m-d H:i:s")
    ];
});

$factory->define(App\Models\Auth\Roles\Role::class, function (Faker\Generator $faker) {

    $slugify = new Slugify();
    $name = $faker->word . ' ' .(time() + rand(1,999999));
    return [
        'name' => $name,
        'slug' => $slugify->slugify($name),
        'permissions' => [$faker->word => true, $faker->word => true],
    ];
});
