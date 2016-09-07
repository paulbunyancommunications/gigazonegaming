<?php

use Illuminate\Database\Migrations\Migration;

class SetMaxNumTeamsForLol2016 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tournament = $this->getTournament();
        $tournament->max_players = 5;
        $tournament->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tournament = $this->getTournament();
        $tournament->max_players = 0;
        $tournament->save();
    }

    private function getTournament()
    {
        return \App\Models\Championship\Tournament::where('name', '=', 'gigazone-gaming-2016-league-of-legends')->first();
    }
}
