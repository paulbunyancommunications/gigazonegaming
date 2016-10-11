<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMaximunNumberOfPlayersInTeamOnTournamentTable extends Migration
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
        $tournamentUpdated = \App\Models\Championship\Tournament::find(1)->update(['max_players'=> 5]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::connection('mysql_champ')->hasColumn('tournaments','max_players')) {
            Schema::connection('mysql_champ')->table('tournaments', function (Blueprint $table) {
                $table->dropColumn('max_players');
            });
        }
    }


}
