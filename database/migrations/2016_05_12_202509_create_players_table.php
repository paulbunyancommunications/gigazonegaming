<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql_champ')->hasTable('players')) {
            Schema::connection('mysql_champ')->create('players', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->increments('id');
                $table->string('username')->unique();
                $table->string('email')->unique();
                $table->string('name');
                $table->string('phone');
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
        if (Schema::connection('mysql_champ')->hasTable('players')) {
            Schema::connection('mysql_champ')->drop('players');
        }
    }
}
