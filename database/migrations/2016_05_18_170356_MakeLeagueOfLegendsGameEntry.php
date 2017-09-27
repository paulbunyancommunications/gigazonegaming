<?php

use App\Models\Championship\Game;
use Illuminate\Database\Migrations\Migration;

class MakeLeagueOfLegendsGameEntry extends Migration
{

    protected $name = 'league-of-legends';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $game = Game::where('name', $this->name)->first();
        if (!$game) {
            $newGame = new Game();
            $newGame->setAttribute('name', $this->name);
            $newGame->setAttribute('title', 'League of Legends');
            $newGame->setAttribute('uri', 'http://leagueoflegends.com/');
            $newGame->save();

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $game = \App\Models\Championship\Game::where('name', $this->name)->first();
        $hasTable = false;
        if(Schema::connection('mysql_champ')->hasTable('player_relations')) {
            $hasTable = true;
        }
//        dd($game);
        if ($game) {
            $teams = $game->teams();
            foreach ($teams as $team){
                if($hasTable) {
                    if (\App\Models\Championship\Relation\PlayerRelation::where([
                        ["relation_id", "=", $team->id],
                        ["relation_type", "=", \App\Models\Championship\Team::class],
                    ])->exists()
                    ) {
                        \App\Models\Championship\Relation\PlayerRelation::where([
                            ["relation_id", "=", $team->id],
                            ["relation_type", "=", \App\Models\Championship\Team::class],
                        ])->delete();
                    }
                }
                $team->delete();
            }
            $tournaments = $game->tournaments();
            foreach ($tournaments as $tournament){
                if($hasTable) {
                    if (\App\Models\Championship\Relation\PlayerRelation::where([
                        ["relation_id", "=", $tournament->id],
                        ["relation_type", "=", \App\Models\Championship\Tournament::class],
                    ])->exists()
                    ) {
                        \App\Models\Championship\Relation\PlayerRelation::where([
                            ["relation_id", "=", $tournament->id],
                            ["relation_type", "=", \App\Models\Championship\Tournament::class],
                        ])->delete();
                    }
                }
                $tournament->delete();
            }
            if($hasTable) {
                if (\App\Models\Championship\Relation\PlayerRelation::where([
                    ["relation_id", "=", $game->id],
                    ["relation_type", "=", \App\Models\Championship\Game::class],
                ])->exists()
                ) {
                    \App\Models\Championship\Relation\PlayerRelation::where([
                        ["relation_id", "=", $game->id],
                        ["relation_type", "=", \App\Models\Championship\Game::class],
                    ])->delete();
                }
            }
            $game->delete();
        }
    }
}
