<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewPivotTableAndRemoveOtherPivotTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $allPlayers = \App\Models\Championship\Player::
        join('player_team', 'players.id', '=', 'player_team.player_id')
            ->join('player_tournament', 'players.id', '=', 'player_tournament.player_id')
            ->join('tournaments', 'tournaments.id', '=', 'player_tournament.tournament_id')
            ->join('games', 'games.id', '=', 'tournaments.game_id')
            ->select('players.id as player_id', 'games.id as game_id','player_team.verification_code as token', 'player_team.team_id', 'player_tournament.tournament_id as tournament_id')
            ->get()->toArray();

        if (!Schema::connection('mysql_champ')->hasTable('game_player_team_tournament')) {
            Schema::connection('mysql_champ')->create('game_player_team_tournament', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer("player_id")->index()->references('id')->on('players');
                $table->integer("game_id")->index()->references('id')->on('games');
                $table->integer("team_id")->index()->references('id')->on('teams');
                $table->integer("tournament_id")->index()->references('id')->on('tournaments');
                $table->string("token")->default(null);
                $table->timestamps();
                $table->unique(['player_id', 'tournament_id']);
            });
        }
        DB::connection('mysql_champ')->table('game_player_team_tournament')->insert($allPlayers);

        if (Schema::connection('mysql_champ')->hasTable('player_tournament')) {
            Schema::connection('mysql_champ')->dropIfExists('player_tournament');
        }
        if (Schema::connection('mysql_champ')->hasTable('player_team')) {
            Schema::connection('mysql_champ')->dropIfExists('player_team');
        }
        if (Schema::connection('mysql_champ')->hasTable('game_player')) {
            Schema::connection('mysql_champ')->dropIfExists('game_player');
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $contents = json_decode(json_encode(DB::connection('mysql_champ')->table('game_player_team_tournament')->get()),TRUE);

        if (!Schema::connection('mysql_champ')->hasTable('player_team')) {
            Schema::connection('mysql_champ')->create('player_team', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->increments('id');
                $table->integer("player_id")->index()->references('id')->on('players');
                $table->integer("team_id")->index()->references('id')->on('teams');
                $table->string("verification_code");
                $table->timestamps();
            });
        }
        if (!Schema::connection('mysql_champ')->hasTable('player_tournament')) {
            Schema::connection('mysql_champ')->create('player_tournament', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->increments('id');
                $table->integer("player_id")->index()->references('id')->on('players');
                $table->integer("tournament_id")->index()->references('id')->on('tournaments');
                $table->timestamps();
            });
        }
        if (!Schema::connection('mysql_champ')->hasTable('game_player')) {
            Schema::connection('mysql_champ')->create('game_player', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->increments('id');
                $table->integer("game_id")->index()->references('id')->on('games');
                $table->integer("player_id")->index()->references('id')->on('players');
                $table->timestamps();
            });
        }
        foreach ($contents as $key => $content){
            DB::connection('mysql_champ')->table('game_player')->insert(['player_id'=>$content['player_id'], 'game_id'=>$content['game_id']]);
            DB::connection('mysql_champ')->table('player_team')->insert(['player_id'=>$content['player_id'], 'team_id'=>$content['team_id'], 'verification_code'=>$content['token']]);
            DB::connection('mysql_champ')->table('player_tournament')->insert(['player_id'=>$content['player_id'], 'tournament_id'=>$content['tournament_id']]);
        }
        if (Schema::connection('mysql_champ')->hasTable('game_player_team_tournament')) {
            Schema::connection('mysql_champ')->dropIfExists('game_player_team_tournament');
        }
    }
}
