<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveTeamIdFromPlayers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //team_id
        if (Schema::connection('mysql_champ')->hasColumn('players', 'team_id')) {
            Schema::connection('mysql_champ')->table('players', function (Blueprint $table) {
                $table->dropColumn('team_id');
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
        if (!Schema::connection('mysql_champ')->hasColumn('players', 'team_id')) {
            Schema::connection('mysql_champ')->table('players', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->integer('team_id')->default(0)->after('username');
            });
        }
    }
}
