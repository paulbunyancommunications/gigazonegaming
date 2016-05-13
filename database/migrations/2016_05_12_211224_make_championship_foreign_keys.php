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
         * Add foreign keys for the players table to game
         */
        Schema::connection('mysql_champ')->table('players', function (Blueprint $table) {
            $table->integer('team_id')->unsigned();
            $table->foreign('team_id')->references('id')->on('teams');
        });

        /**
         * Add foreign keys for the teams table to captain and game
         */
        Schema::connection('mysql_champ')->table('teams', function (Blueprint $table) {
            $table->integer('game_id')->unsigned();
            $table->foreign('game_id')->references('id')->on('games');
        });
        
        /**
         * Add foreign keys for the individual players table to game
         */
        Schema::connection('mysql_champ')->table('individual_players', function (Blueprint $table) {
            $table->integer('game_id')->unsigned();
            $table->foreign('game_id')->references('id')->on('games');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql_champ')->table('players', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
        });

        Schema::connection('mysql_champ')->table('teams', function (Blueprint $table) {
            $table->dropForeign(['game_id']);
        });

        Schema::connection('mysql_champ')->table('individual_players', function (Blueprint $table) {
            $table->dropForeign(['game_id']);
        });
    }
}
