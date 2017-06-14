<?php

use Illuminate\Database\Seeder;

class SignUpUniqueWithValidatorTesterSeeder extends Seeder
{
    public $nelson_variables;
    const TEAM_A_NAME = "Tester Team Unique Width A";//same as in the test class for uniquewith validator
    const TEAM_B_NAME = "Tester Team Unique Width B";//same as in the test class for uniquewith validator
    const TOURNAMENT_A_NAME = "Tester_Tournament_Unique_Width_A";//same as in the test class for uniquewith validator
    const TOURNAMENT_B_NAME = "Tester_Tournament_Unique_Width_B";//same as in the test class for uniquewith validator

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(WpTestAdminUserSeed::class);
        $faker = \Faker\Factory::create();
        $games = [];
        $tournaments = [];
        $teams = [];
        $playerWithoutRelations = [];
        $playerWithRelations = [];
        $sp_game =  \App\Models\Championship\Game::create(
        [
            'name' => "Tester Game Unique Width",
            'title' => "The Tester Game",
            'description' => $faker->paragraph(),
            'uri' => $faker->url(),
        ])->toArray();
        $sp_tournamentA = \App\Models\Championship\Tournament::create(
            [
                'name' => $this::TOURNAMENT_A_NAME,
                'game_id' => $sp_game['id'],
                'sign_up_open' => $faker->dateTimeBetween('+0 minutes', '+1 minute'),
                'sign_up_close' => $faker->dateTimeBetween('+6 days', '+10 week'),
                'occurring' => $faker->dateTimeBetween('+3 month', '+6 months'),
                'max_players' => 6,
            ])->toArray();
        $sp_tournamentB = \App\Models\Championship\Tournament::create(
            [
                'name' => $this::TOURNAMENT_B_NAME,
                'game_id' => $sp_game['id'],
                'sign_up_open' => $faker->dateTimeBetween('+0 minutes', '+1 minute'),
                'sign_up_close' => $faker->dateTimeBetween('+6 days', '+10 week'),
                'occurring' => $faker->dateTimeBetween('+3 month', '+6 months'),
                'max_players' => 3,
            ])->toArray();
        $sp_team = \App\Models\Championship\Team::create(
            [
                'name' => $this::TEAM_A_NAME,
                'emblem' => $faker->imageUrl(),
                'tournament_id' => $sp_tournamentA['id'],
                'captain' => 0
            ])->toArray();
        // create the tester user if not already created
    }
}
