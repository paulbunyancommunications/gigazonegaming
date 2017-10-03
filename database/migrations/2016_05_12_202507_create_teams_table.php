<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql_champ')->hasTable('teams')) {
            Schema::connection('mysql_champ')->create('teams', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->increments('id');
                $table->string('name');
                $table->string('emblem');
                $table->integer('captain');
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
        if (!Schema::connection('mysql_champ')->hasTable('teams')) {
            Schema::connection('mysql_champ')->drop('teams');
        }
    }
}
