<?php

use Illuminate\Database\Seeder;

class SearchControllerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        // make specific game, tournament, team, and player for SearchController tests

        /** @var \App\Models\Championship\Game $game name tester game */
        $game = factory(App\Models\Championship\Game::class)->create([
            'name' => strtolower(get_class($this)).'-game',
            'title' => get_class($this). ' Game',
            'uri' => $faker->url
        ]);

        /** @var \App\Models\Championship\Tournament $tournament make tournament */
        $tournament = factory(App\Models\Championship\Tournament::class)->create([
             'name' => strtolower(get_class($this)).'-tournament',
             'game_id' => $game->id,
        ]);

        /** @var \App\Models\Championship\Team $team create new team */
        $team = factory(App\Models\Championship\Team::class)->create([
            'name' => get_class($this),
            'tournament_id' => $tournament->id,
            'emblem' => 'http://example.com/image.jpg'
        ]);

        /** @var \App\Models\Championship\Player $player create player */
        $player = factory(App\Models\Championship\Player::class)->create([
            'name' => strtolower(get_class($this)).'-firstname '.strtolower(get_class($this)).'-lastname',
            'username' => strtolower(get_class($this)).'-username',
        ]);

        /** Player to team relation */
        factory(App\Models\Championship\PlayerRelation::class)->create([
            'player_id' => $player->id,
            'relation_id' => $team->id,
            'relation_type' => App\Models\Championship\Team::class,
        ]);

        /** Player to tournament relation */
        factory(App\Models\Championship\PlayerRelation::class)->create([
            'player_id' => $player->id,
            'relation_id' => $tournament->id,
            'relation_type' => App\Models\Championship\Tournament::class,
        ]);

        /** Player to game relation */
        factory(App\Models\Championship\PlayerRelation::class)->create([
            'player_id' => $player->id,
            'relation_id' => $game->id,
            'relation_type' => App\Models\Championship\Game::class,
        ]);



    }
}
