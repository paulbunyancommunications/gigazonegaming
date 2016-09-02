<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlayerTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql_champ')->hasTable('player_team')) {
            Schema::connection('mysql_champ')->create('player_team', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->increments('id');
                $table->integer("player_id")->index()->references('id')->on('players');
                $table->integer("team_id")->index()->references('id')->on('teams');
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
        Schema::connection('mysql_champ')->dropIfExists('player_team');
    }
}
