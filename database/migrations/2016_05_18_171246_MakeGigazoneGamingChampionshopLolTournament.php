<?php

use App\Models\Championship\Game;
use App\Models\Championship\Tournament;
use Illuminate\Database\Migrations\Migration;

class MakeGigazoneGamingChampionshopLolTournament extends Migration
{

    protected $name = 'gigazone-gaming-2016-league-of-legends';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $game = Game::where('name', 'league-of-legends')->first();
        $tournament = Tournament::where('name', $this->name)->first();
        if (!$tournament) {
            $newTournament = new Tournament();
            $newTournament->setAttribute('game_id', $game->id);
            $newTournament->setAttribute('name', $this->name);
            $newTournament->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tournament = Tournament::where('name', $this->name)->first();
        if ($tournament) {
            Tournament::where('id', $tournament->id)->delete();
        }
    }
}
