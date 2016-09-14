<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PolymorphicTableForPlayers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql_champ')->hasTable('player_relations')) {
            Schema::connection('mysql_champ')->create('player_relations', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer("player_id")->index()->references('id')->on('players');
                $table->integer("relation_id");
                $table->string("relation_type");
                $table->timestamps();
            });
//            $allPlayers = \App\Models\Championship\Player::
            $allPlayers = json_decode(json_encode(DB::connection('mysql_champ')->table('game_player_team_tournament')->get()),TRUE);

            foreach ($allPlayers as $key => $player) {
                if (isset($player['team_id']) and $player['team_id'] != '' and $player['team_id'] != [] and $player['team_id'] != null) {
                    DB::connection('mysql_champ')->table('player_relations')
                        ->insert([
                            'relation_type' => 'App\Models\Championship\Team',
                            'relation_id' => $player['team_id'],
                            "player_id" => $player['player_id']
                        ]);
                }
                //we wont care about game relations
//                DB::connection('mysql_champ')
//                    ->table('player_relations')
//                    ->insert([
//                            'relation_type' => 'App\Models\Championship\Game',
//                            'relation_id' => $player['game_id'],
//                            "player_id" => $player['player_id']
//                        ]);
                DB::connection('mysql_champ')
                    ->table('player_relations')
                    ->insert(
                        [
                            'relation_type' => 'App\Models\Championship\Tournament',
                            'relation_id' => $player['tournament_id'],
                            "player_id" => $player['player_id']
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
        if (Schema::connection('mysql_champ')->hasTable('player_relations')) {
            Schema::connection('mysql_champ')->drop('player_relations');
        }
    }
}
