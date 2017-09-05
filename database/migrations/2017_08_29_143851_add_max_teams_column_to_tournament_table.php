<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMaxTeamsColumnToTournamentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql_champ')->hasColumn('tournaments', 'max_teams')) {
            Schema::connection('mysql_champ')->table('tournaments', function (Blueprint $table) {
                $table->integer('max_teams')->default(0)->after('max_players');
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
        if (Schema::connection('mysql_champ')->hasColumn('tournaments','max_teams')) {
            Schema::connection('mysql_champ')->table('tournaments', function (Blueprint $table) {
                $table->dropColumn('max_teams');
            });
        }
    }
}
