<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropingExtraUnnecessaryKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {//now deleting unnecessary keys

        if(!Schema::connection('mysql_champ')->hasTable('games_players')) {
            Schema::connection('mysql_champ')->table('games_players', function (Blueprint $table) {
                $table->dropForeign('game_id');
                $table->dropForeign('player_id');
            });
        }

        if(!Schema::connection('mysql_champ')->hasTable('players_teams')) {
            Schema::connection('mysql_champ')->table('players_teams', function (Blueprint $table) {
                $table->dropForeign('team_id');
                $table->dropForeign('player_id');
            });
        }

        if(!Schema::connection('mysql_champ')->hasTable('players_tournaments')) {
            Schema::connection('mysql_champ')->table('players_tournaments', function (Blueprint $table) {
                $table->dropForeign('tournament_id');
                $table->dropForeign('player_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
