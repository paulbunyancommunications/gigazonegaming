<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeChampionshipForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Add foreign keys for tournament table
         */
        if (!Schema::connection('mysql_champ')->hasColumn('tournaments', 'game_id')) {
            Schema::connection('mysql_champ')->table('tournaments', function (Blueprint $table) {
                $table->integer('game_id')->unsigned();
                $table->foreign('game_id')->references('id')->on('games');
            });
        }
        /**
         * Add foreign keys for the players table to game
         */
        if (!Schema::connection('mysql_champ')->hasColumn('players', 'team_id')) {
            Schema::connection('mysql_champ')->table('players', function (Blueprint $table) {
                $table->integer('team_id')->unsigned();
            });
        }

        /**
         * Add foreign keys for the teams table to captain and game
         */
        if (!Schema::connection('mysql_champ')->hasColumn('teams', 'tournament_id')) {
            Schema::connection('mysql_champ')->table('teams', function (Blueprint $table) {
                $table->integer('tournament_id')->unsigned();
                $table->foreign('tournament_id')->references('id')->on('tournaments');
            });
        }
        
        /**
         * Add foreign keys for the individual players table to game
         */
        if (!Schema::connection('mysql_champ')->hasColumn('individual_players', 'game_id')) {
            Schema::connection('mysql_champ')->table('individual_players', function (Blueprint $table) {
                $table->integer('game_id')->unsigned();
                $table->foreign('game_id')->references('id')->on('games');
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
        if (Schema::connection('mysql_champ')->hasColumn('tournaments', 'game_id')) {
            Schema::connection('mysql_champ')->table('tournaments', function (Blueprint $table) {
                $table->dropForeign('tournaments_game_id_foreign');
                $table->dropColumn(['game_id']);
            });
        }
        if (Schema::connection('mysql_champ')->hasColumn('players', 'team_id')) {
            Schema::connection('mysql_champ')->table('players', function (Blueprint $table) {
                //$table->dropForeign('players_team_id_foreign');
                $table->dropColumn(['team_id']);
            });
        }
        if (Schema::connection('mysql_champ')->hasColumn('teams', 'tournament_id')) {
            Schema::connection('mysql_champ')->table('teams', function (Blueprint $table) {
                $table->dropForeign('teams_tournament_id_foreign');
                $table->dropColumn(['tournament_id']);
            });
        }
        if (Schema::connection('mysql_champ')->hasColumn('individual_players', 'game_id')) {
            Schema::connection('mysql_champ')->table('individual_players', function (Blueprint $table) {
//                $table->dropForeign('individual_players_game_id_foreign');
                $table->dropColumn(['game_id']);
            });
        }
    }
}
