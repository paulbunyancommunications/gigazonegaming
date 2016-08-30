<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayersTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_champ')->create('players_teams', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("player_id")->references('id')->on('players');
            $table->integer("team_id")->references('id')->on('teams');
            $table->string("verification_code");
            $table->timestamps();
        });
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
