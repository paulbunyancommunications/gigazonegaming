<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlayersTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql_champ')->hasTable('players_teams')) {
            Schema::connection('mysql_champ')->create('players_teams', function (Blueprint $table) {
                $table->increments('id');
                $table->integer("player_id")->references('id')->on('players');
                $table->integer("team_id")->references('id')->on('teams');
                $table->string("verification_code");
                $table->timestamps();
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
        Schema::connection('mysql_champ')->dropIfExists('players_teams');
    }
}
