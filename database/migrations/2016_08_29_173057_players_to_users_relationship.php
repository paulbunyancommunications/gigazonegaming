<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PlayersToUsersRelationship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_champ')->create('players_users', function (Blueprint $table) {
            $table->integer('player_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->primary(['player_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql_champ')->dropIfExists('players_users');
    }
}
