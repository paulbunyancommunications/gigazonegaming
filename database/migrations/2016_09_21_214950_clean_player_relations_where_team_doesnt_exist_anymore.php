<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CleanPlayerRelationsWhereTeamDoesntExistAnymore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::connection('mysql_champ')->hasTable( 'Team') and Schema::connection('mysql_champ')->hasTable('player_relations') ) {
            $teamsRelations = \App\Models\Championship\Relation\PlayerRelation::where('relation_type', '=', \App\Models\Championship\Team::class)->get();
            foreach ($teamsRelations as $key => $team) {
                if (\App\Models\Championship\Team::where('id', '=', $team->relation_id)->get()->toArray() == []) {
                    $team->delete();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
