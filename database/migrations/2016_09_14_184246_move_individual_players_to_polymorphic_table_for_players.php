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
        $allPlayers = \App\Models\Championship\Player::all();
        foreach ($allPlayers as $key => $player) {
            try {
                \App\Models\Championship\Relation\PlayerRelation::firstOrCreate([
                    'relation_type' => \App\Models\Championship\Tournament::class,
                    'relation_id' => 1,
                    "player_id" => $player->id
                ]);
            } catch (\Exception $ex) {
                throw new \Exception('Could not create player with '. json_encode($player).'. Error: '.$ex->getMessage());
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
