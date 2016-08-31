<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlayersTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql_champ')->hasTable('players_tournaments')) {
            Schema::connection('mysql_champ')->create('players_tournaments', function (Blueprint $table) {
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
        Schema::connection('mysql_champ')->dropIfExists('players_tournaments');
    }
}
