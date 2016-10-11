<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameRelationshipForEachPlayer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tournaments = \App\Models\Championship\PlayerRelation::where('relation_type', '=', \App\Models\Championship\Tournament::class)->get();
        foreach ($tournaments as $key => $tournament){
            $the_tournament = \App\Models\Championship\Tournament::where('id', '=', $tournament->relation_id)->first();
            $p_id = $tournament->player_id;
            $g_id = $the_tournament->game_id;
            $relation = new \App\Models\Championship\PlayerRelation();
            $relation->player_id = $p_id;
            $relation->relation_id = $g_id;
            $relation->relation_type = \App\Models\Championship\Game::class;
            $exists = \App\Models\Championship\Game::find($g_id)->hasPlayerID($p_id);
            if(!$exists){
                $relation->save();
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
