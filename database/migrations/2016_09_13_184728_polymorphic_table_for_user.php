<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PolymorphicTableForUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql_champ')->hasTable('player_relations')) {
            Schema::connection('mysql_champ')->create('player_relations', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer("player_id")->index()->references('id')->on('players');
                $table->integer("relation_id");
                $table->string("relation_type");
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
        if (Schema::connection('mysql_champ')->hasTable('player_relations')) {
            Schema::connection('mysql_champ')->drop('player_relations');
        }
    }
}
