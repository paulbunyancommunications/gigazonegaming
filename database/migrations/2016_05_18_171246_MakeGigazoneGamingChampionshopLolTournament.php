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
        if (Schema::connection('mysql_champ')->hasTable('games')) {
            $game = Game::where('name', 'league-of-legends')->first();
            if (Schema::connection('mysql_champ')->hasTable('tournaments')) {
                if (!Tournament::where('name', $this->name)->exists()) {
                    $tournament = Tournament::where('name', $this->name)->first();
                    $newTournament = new Tournament();
                    $newTournament->setAttribute('game_id', $game->id);
                    $newTournament->setAttribute('name', $this->name);
                    $newTournament->save();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::connection('mysql_champ')->hasTable('tournaments')) {
            if (!Tournament::where('name', $this->name)->exists()) {
                $tournament = Tournament::where('name', $this->name)->first();
                Tournament::where('id', $tournament->id)->delete();
            }
        }
    }
}
