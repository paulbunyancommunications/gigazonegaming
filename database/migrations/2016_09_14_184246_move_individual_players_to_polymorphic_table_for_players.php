<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveIndividualPlayersToPolymorphicTableForPlayers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //we only have a game and as every single player has the game id of 2 we suppose that
        // the player already signed up for the lol tournament with an ID of 1
        // (THERE ARE NO OTHER TOURNAMENTS AT THIS TIME!)
        $allPlayers = \App\Models\Championship\Player::all()->toArray();
        foreach ($allPlayers as $key => $player) {
            unset($player['game_id']);
            $player['team_id'] = null;
            $newPlayer = \App\Models\Championship\Player::create($player);
            $relation = \App\Models\Championship\PlayerRelation::create([
                        'relation_type' => 'App\Models\Championship\Tournament',
                        'relation_id' => 1,
                        "player_id" => $newPlayer->id
                    ]);
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
