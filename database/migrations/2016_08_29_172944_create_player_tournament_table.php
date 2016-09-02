<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlayerTournamentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql_champ')->hasTable('player_tournament')) {
            Schema::connection('mysql_champ')->create('player_tournament', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->increments('id');
                $table->integer("player_id")->index()->references('id')->on('players');
                $table->integer("tournament_id")->index()->references('id')->on('tournaments');
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
        Schema::connection('mysql_champ')->dropIfExists('player_tournament');
    }
}
