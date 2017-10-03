<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql_champ')->hasTable('games')) {
            Schema::connection('mysql_champ')->create('games', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->increments('id');
                $table->string('name')->index()->unique();
                $table->string('title')->index();
                $table->text('description');
                $table->string('uri');
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
        if (Schema::connection('mysql_champ')->hasTable('games')) {
            Schema::connection('mysql_champ')->drop('games');
        }
    }
}
