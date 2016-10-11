<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveIndividualPlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::connection('mysql_champ')->hasTable('individual_players')) {
            Schema::connection('mysql_champ')->drop('individual_players');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::connection('mysql_champ')->hasTable('individual_players')) {
            Schema::connection('mysql_champ')->create('individual_players', function (Blueprint $table) {
                $table->increments('id');
                $table->string('username')->unique();
                $table->string('email')->unique();
                $table->string('name');
                $table->string('phone');
                $table->timestamps();
            });
        }
    }
}
