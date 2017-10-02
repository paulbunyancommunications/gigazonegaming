<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveTeamPlayersToPolymorphicTableForPlayers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::connection('mysql_champ')->hasTable('player_relations')) {
            $allPlayers = \App\Models\Championship\Player::select('id', 'team_id')->get()->toArray();
            foreach ($allPlayers as $key => $player) {
                DB::connection('mysql_champ')->table('player_relations')
                    ->insert([
                        'relation_type' => 'App\Models\Championship\Team',
                        'relation_id' => $player['team_id'],
                        "player_id" => $player['id'],
                        "created_at" => \Carbon\Carbon::now(),
                        "updated_at" => \Carbon\Carbon::now()

                    ]);

                // the player already signed up for the lol tournament with an ID of 1(THERE ARE NO OTHER TOURNAMENTS AT THIS TIME!)
                DB::connection('mysql_champ')
                    ->table('player_relations')
                    ->insert(
                        [
                            'relation_type' => 'App\Models\Championship\Tournament',
                            'relation_id' => 1,
                            "player_id" => $player['id'],
                            "created_at" => \Carbon\Carbon::now(),
                            "updated_at" => \Carbon\Carbon::now()
                        ]);
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
        // we don't need one as we would still, as it is right now,
        // have all the info added to the polymorphic table in their
        // respective tables.
    }
}
