<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTeamNameTournamentIdConstraint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::connection('mysql_champ')->hasColumns('teams',['name','tournament_id'])) {
            Schema::connection('mysql_champ')->table('teams', function (Blueprint $table) {
                $table->unique(['name', 'tournament_id']);
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
        if(Schema::connection('mysql_champ')->hasColumns('teams',['name','tournament_id'])) {
            Schema::connection('mysql_champ')->table('teams', function (Blueprint $table) {
                $table->dropUnique('teams_name_tournament_id_unique');
            });
        }
    }
}
