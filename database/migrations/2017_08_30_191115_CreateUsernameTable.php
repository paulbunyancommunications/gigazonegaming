<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsernameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::connection('mysql_champ')->hasTable('usernames')) {
            Schema::connection('mysql_champ')->drop('usernames');
        }
        Schema::connection('mysql_champ')->create('usernames', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('avatar_url');
            $table->integer('player_id');
            $table->integer('tournament_id');
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
        Schema::connection('mysql_champ')->drop('usernames');
    }
}
