<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRowToTournamentsMaxNumberOfPlayers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_champ')->table('tournaments', function (Blueprint $table) {
            $table->integer('max_players')->default(0)->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql_champ')->table('tournaments', function (Blueprint $table) {
            $table->dropColumn('max_players');
        });
    }
}
