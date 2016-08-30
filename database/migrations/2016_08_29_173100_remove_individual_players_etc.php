<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveIndividualPlayersEtc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::connection('mysql_champ')->hasTable('players')) {
            $player = \App\Models\Championship\Player::all()->toArray();

            if (isset($player[0]['team_id'])) {
                $this->back_players_table($player);
                foreach ($player as &$p) {
                    $string = 'none_yet';// this is to create a string that would allow team mates to get assign to a team if they didnt have one
                    $team_id = $p['team_id'];
                    unset($p['team_id']);
                    $team_info = \App\Models\Championship\Team::where('id', $team_id)->select('captain', 'tournament_id')->get()->toArray();
//                if(10 == $team_id){dd($team_info);}
                    if (isset($team_info[0]) and $team_info != [] and $team_info[0] != null) {
                        $team_info = $team_info[0];
                        if (isset($team_info['captain']) and $team_info['captain'] == $p['id']) {
                            $string = str_random(8); //it is a captain so create a string that he or she can send to add players
                        } //is it a captain ????

                        //create the pivot table info for players and teams
                        $p_te = new \App\Models\Championship\Players_Teams();
                        $p_te->player_id = $p['id'];
                        $p_te->team_id = $team_id;
                        $p_te->verification_code = $string;
                        $p_te->save();

//            dd($p['id']);
                        //create the pivot table info for players and tournaments
//            dd($team_info);

                        $p_to = new App\Models\Championship\Players_Tournaments();
                        $p_to->player_id = $p['id'];
                        $p_to->tournament_id = $team_info['tournament_id'];
                        $p_to->save();
                    } else {
                        \App\Models\Championship\Player::destroy($p['id']);
                    }

                }

                //all players (not individual) are set to go, now we can remove the column that is not need it anymore.

            }
            Schema::connection('mysql_champ')->table('players', function ($table) {
                $table->dropForeign('players_team_id_foreign');
                $table->dropColumn('team_id');
            });
        }

        if (Schema::connection('mysql_champ')->hasTable('individual_players')) {
            $indPlayer = \App\Models\Championship\IndividualPlayer::all()->toArray();
            if (isset($indPlayer[0]['game_id'])) {

                $this->back_individualPlayers_table($indPlayer);
                foreach ($indPlayer as &$p) {
//                dd($p);
                    $string = 'none_yet';// this is to create a string that would allow team mates to get assign to a team if they didnt have one
                    $game_id = $p['game_id'];
                    $tournaments = \App\Models\Championship\Tournament::where('game_id', $p['game_id'])->get()->toArray();
                    unset($p['id']);
                    unset($p['game_id']);
                    $newPlayer = \App\Models\Championship\Player::create($p);
                    //create the pivot table info for players and tournaments
                    foreach ($tournaments as $tournament) { // this is a bold fix because we just go with every torunament that is for that game, even if the user didnt sign for it, but because there is only one game hopefully will work and we wont need it again :)
                        \App\Models\Championship\Players_Tournaments::create(['player_id' => $newPlayer->id, 'tournament_id' => $tournament['id']]);
                    }
                }
                //all individual players are in the player table and in the pivot table for tournaments now we can drop the table

            }
            Schema::connection('mysql_champ')->drop('individual_players');
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql_champ')->create('individual_players', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('name');
            $table->string('phone');
            $table->timestamps();
        });
        Schema::connection('mysql_champ')->table('players', function ($table) {
            $table->integer('team_id')->default(0);
        });
    }

    /**
     * @param $indPlayer
     * @param $player
     */
    public function back_individualPlayers_table($indPlayer)
    {
        $fp = fopen(dirname(dirname(__FILE__)) . '/individualPlayersTableBkup.csv', 'a+'); ///saving info in case something happen
        foreach ($indPlayer as $fields) {
            fputcsv($fp, $fields, chr(9));
        }
        fclose($fp);
    }
    /**
     * @param $indPlayer
     * @param $player
     */
    public function back_players_table($player)
    {
        $fp = fopen(dirname(dirname(__FILE__)) . '/PlayersTableBkup.csv', 'a+'); ///saving info in case something happen
        foreach ($player as $fields) {
            fputcsv($fp, $fields, chr(9));
        }
        fclose($fp);
    }
}
