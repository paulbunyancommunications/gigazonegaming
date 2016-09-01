<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropPlayersUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_champ')->dropIfExists('players_users');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::connection('mysql_champ')->hasTable('players_users')) {
            Schema::connection('mysql_champ')->create('players_users', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->integer('player_id')->index()->unsigned();
                $table->integer('user_id')->index()->unsigned();
                $table->nullableTimestamps();
                $table->engine = 'InnoDB';
                $table->primary(['player_id', 'user_id']);
            });
        }
    }
}
