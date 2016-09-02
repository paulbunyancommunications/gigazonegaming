<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PlayerToUserRelationship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql_champ')->hasTable('player_user')) {
            Schema::connection('mysql_champ')->create('player_user', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->integer('player_id')->index()->unsigned();
                $table->integer('user_id')->index()->unsigned();
                $table->nullableTimestamps();
                $table->engine = 'InnoDB';
                $table->primary(['player_id', 'user_id']);
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
        Schema::connection('mysql_champ')->dropIfExists('player_user');
    }
}
