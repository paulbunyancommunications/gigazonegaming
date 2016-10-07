<?php

use Illuminate\Database\Seeder;
use \App\Models\Championship\Game;
use \App\Models\Championship\Tournament;
use \App\Models\Championship\Team;
use \App\Models\Championship\Player;

class BackendTestSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Team::class, 76)->create();
        factory(Player::class, 371)->create();
        $faker = \Faker\Factory::create();
//        $games = [];
//        $tournaments = [];
//        $teams = [];
//        $playerWithoutRelations = [];
//        $playerWithRelations = [];
//        for($i=0; $i<9; $i++) {
//            $games[] = Game::create(
//                [
//                    'name' => implode('-', $faker->words()),
//                    'title' => $faker->sentence(),
//                    'description' => $faker->paragraph(),
//                    'uri' => $faker->url(),
//                ]);
//        }
//        for($i=0; $i<14; $i++) {
//            $tournaments[] = Tournament::create(
//                [
//                    'name' => implode('-', $faker->words(4)),
//                    'game_id' => array_random($games)->id,
//                    'sign_up_open' => $faker->dateTimeBetween('+30 minutes', '+1 day'),
//                    'sign_up_close' => $faker->dateTimeBetween('+2 days', '+1 week'),
//                    'occurring' => $faker->dateTimeBetween('+1 month', '+2 months'),
//                    'max_players' => $faker->randomDigitNotNull,
//                ] );
//        }
//        for($i=0; $i<74; $i++) {
//            $teams[] = Team::create(
//                [
//                    'name' => implode('-', $faker->words()),
//                    'emblem' => $faker->imageUrl(),
//                    'tournament_id' => array_random($games)->id,
//                    'captain' => null
//                ]);
//        }
//        // create the tester user if not already created
//        for($i=0; $i<223; $i++) {
//            $playerWithRelations[] = Player::create(
//                [
//                    'name' => $faker->name,
//                    'username' => $faker->userName,
//                    'email' => $faker->email,
//                    'phone' => $faker->phoneNumber,
//                    'user_id' => $faker->numberBetween(20, 22260)
//                ]);
//        }// create the tester user if not already created
//        foreach ($playerWithRelations as $k => $player){
//            $relation[] = \App\Models\Championship\PlayerRelation::create(
//                [
//                    'player_id' => $player->id,
//                    'relation_type' => Team::class,
//                    'relation_id' => array_random($teams)->id,
//                ]);
//        }
//        for($i=0; $i<223; $i++) {
//            $playerWithoutRelations[] = Player::create(
//                [
//                    'name' => $faker->name,
//                    'username' => $faker->userName,
//                    'email' => $faker->email,
//                    'phone' => $faker->phoneNumber,
//                    'user_id' => $faker->numberBetween(20, 22260)
//                ]);
//        }
    }
}
