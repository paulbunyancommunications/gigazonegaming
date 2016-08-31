<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddRowToTournamentsMaxNumberOfPlayers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql_champ')->hasColumn('tournaments', 'max_players')) {
            Schema::connection('mysql_champ')->table('tournaments', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->integer('max_players')->default(0)->after('name');
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
        if (Schema::connection('mysql_champ')->hasColumn('tournaments', 'max_players')) {
            Schema::connection('mysql_champ')->table('tournaments', function (Blueprint $table) {
                $table->dropColumn('max_players');
            });
        }
    }
}
