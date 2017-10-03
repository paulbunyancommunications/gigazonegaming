<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RepairForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /**
         * Add foreign keys for the teams table to captain and game
         */
        if (!Schema::connection('mysql_champ')->hasColumn('teams','tournament_id')) {
            Schema::connection('mysql_champ')->table('teams', function (Blueprint $table) {
                $table->dropForeign('teams_tournament_id_foreign');
                $table->foreign('tournament_id')->references('id')->on('tournaments')->unsigned()->nullable();
            });
        }

        /**
         * Add foreign keys for tournament table
         */
        if (!Schema::connection('mysql_champ')->hasColumn('tournaments','game_id')) {
            Schema::connection('mysql_champ')->table('tournaments', function (Blueprint $table) {
                $table->dropForeign('tournaments_game_id_foreign');
                $table->foreign('game_id')->references('id')->on('games')->unsigned()->nullable();
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
        //this will be take care of on a different (previous) migration. 2016_05_12_211224_make_championship_foreign_keys
    }
}
