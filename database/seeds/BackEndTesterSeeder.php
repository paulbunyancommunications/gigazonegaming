<?php

use Illuminate\Database\Seeder;

class BackEndTesterSeeder extends Seeder
{
    public $nelson_variables;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        factory(\App\Models\Championship\Team::class, 76)->create();
//        factory(\App\Models\Championship\Player::class, 371)->create();
//        $faker = \Faker\Factory::create();

        $faker = \Faker\Factory::create();
        $games = [];
        $tournaments = [];
        $teams = [];
        $playerWithoutRelations = [];
        $spPlayerWithRelations = [];
        $playerWithRelations = [];
        for($i=0; $i<10; $i++) {
            $games[] = \App\Models\Championship\Game::create(
                [
                    'name' => implode('-', $faker->words()),
                    'title' => $faker->sentence(),
                    'description' => $faker->paragraph(),
                    'uri' => $faker->url(),
                ])->toArray();
        }
        $sp_game =  \App\Models\Championship\Game::create(
        [
            'name' => "Tester Game",
            'title' => "The Tester Game",
            'description' => $faker->paragraph(),
            'uri' => $faker->url(),
        ])->toArray();
        for($i=0; $i<14; $i++) {
            $tournaments[] = \App\Models\Championship\Tournament::create(
                [
                    'name' => implode('-', $faker->words(4)),
                    'game_id' => $games[array_rand($games)]['id'],
                    'sign_up_open' => $faker->dateTimeBetween('+30 minutes', '+1 day'),
                    'sign_up_close' => $faker->dateTimeBetween('+2 days', '+1 week'),
                    'occurring' => $faker->dateTimeBetween('+1 month', '+2 months'),
                    'max_players' => $faker->randomDigitNotNull,
                ] );
        }
        $sp_tournament = \App\Models\Championship\Tournament::create(
            [
                'name' => "Tester Tournament",
                'game_id' => $sp_game['id'],
                'sign_up_open' => $faker->dateTimeBetween('+0 minutes', '+1 minute'),
                'sign_up_close' => $faker->dateTimeBetween('+6 days', '+10 week'),
                'occurring' => $faker->dateTimeBetween('+3 month', '+6 months'),
                'max_players' => 6,
            ] );
        for($i=0; $i<74; $i++) {
            $teams[] = \App\Models\Championship\Team::create(
                [
                    'name' => implode('-', $faker->words()),
                    'emblem' => $faker->imageUrl(),
                    'tournament_id' => $tournaments[array_rand($tournaments)]['id'],
                    'captain' => 0
                ])->toArray();
        }
        $sp_team = \App\Models\Championship\Team::create(
            [
                'name' => "Tester Team",
                'emblem' => $faker->imageUrl(),
                'tournament_id' => $sp_tournament['id'],
                'captain' => 0
            ])->toArray();

        // create the tester user if not already created
        for($i=0; $i<6; $i++) {
            $spPlayerWithRelations[] = \App\Models\Championship\Player::create(
                [
                    'name' => "Tester Player".$i,
                    'username' => "The Tester Player".$i,
                    'email' => $faker->email,
                    'phone' => $faker->phoneNumber,
                    'user_id' => $faker->numberBetween(20, 2226000)
                ])->playerRelationsToAnArrayOfObjectsOfTeamsAndTournamentsAndGames(["Team"=>$sp_team['id']]);
        }
        for($i=0; $i<123; $i++) {
            $playerWithRelations[] = \App\Models\Championship\Player::create(
                [
                    'name' => "Tester Player".($i+6),
                    'username' => "The Tester Player".($i+6),
                    'email' => $faker->email,
                    'phone' => $faker->phoneNumber,
                    'user_id' => $faker->numberBetween(20, 2226000)
                ])->toArray();
        }// create the tester user if not already created
        foreach ($playerWithRelations as $k => $player){
            $relation[] = \App\Models\Championship\PlayerRelation::create(
                [
                    'player_id' => $player['id'],
                    'relation_type' => \App\Models\Championship\Team::class,
                    'relation_id' => $teams[array_rand($teams)]['id'],
                ])->toArray();
        }
        for($i=0; $i<121; $i++) {
            $playerWithoutRelations[] = \App\Models\Championship\Player::create(
                [
                    'name' => $faker->name,
                    'username' => $faker->userName,
                    'email' => $faker->email,
                    'phone' => $faker->phoneNumber,
                    'user_id' => $faker->numberBetween(20, 2226000)
                ])->toArray();
        }
        $this->nelson_variables = [
            'players_s'=>$playerWithoutRelations,
            'players_r'=>$playerWithRelations,
            'teams'=>$teams,
            'tournaments'=>$tournaments,
            'games'=>$games
        ];
    }
}
