<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql_champ')->hasTable('tournaments')) {
            Schema::connection('mysql_champ')->create('tournaments', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->increments('id');
                $table->string('name')->index()->unique();
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
        if (Schema::connection('mysql_champ')->hasTable('tournaments')) {
            Schema::connection('mysql_champ')->drop('tournaments');
        }
    }
}
