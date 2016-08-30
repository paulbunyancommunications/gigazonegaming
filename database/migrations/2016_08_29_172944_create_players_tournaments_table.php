<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayersTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_champ')->create('players_tournaments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("player_id")->references('id')->on('players');
            $table->integer("tournament_id")->references('id')->on('tournaments');
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
        Schema::connection('mysql_champ')->dropIfExists('players_tournaments');
    }
}
