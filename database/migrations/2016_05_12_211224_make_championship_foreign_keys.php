<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeChampionshipForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Add foreign keys for tournament table
         */
        if (!Schema::connection('mysql_champ')->hasColumn('tournaments','game_id')) {
            Schema::connection('mysql_champ')->table('tournaments', function (Blueprint $table) {
                try {
                    $table->integer('game_id')->unsigned()->nullable();
                    $table->foreign('game_id')->references('id')->on('games')->nullable();
                }catch (\Illuminate\Database\QueryException $exception){
                    echo "the key game_id existed";
                } catch (\Exception $e) {
                    // something went wrong elsewhere, handle gracefully
                    echo "the key game_id existed but there was some other error";
                }
            });
        }
        /**
         * Add foreign keys for the players table to game
         */
        if (!Schema::connection('mysql_champ')->hasColumn('players', 'team_id')) {
            Schema::connection('mysql_champ')->table('players', function (Blueprint $table) {
                try {
                    $table->integer('team_id')->unsigned()->nullable();
                    $table->foreign('team_id')->references('id')->on('teams')->nullable();
                }catch (\Illuminate\Database\QueryException $exception){
                    echo "the key team_id existed";
                } catch (\Exception $e) {
                    // something went wrong elsewhere, handle gracefully
                    echo "the key game_id existed but there was some other error";
                }
            });
        }

        /**
         * Add foreign keys for the teams table to captain and game
         */
        if (!Schema::connection('mysql_champ')->hasColumn('teams','tournament_id')) {
            Schema::connection('mysql_champ')->table('teams', function (Blueprint $table) {
                try {
                    $table->integer('tournament_id')->unsigned()->nullable();
                    $table->foreign('tournament_id')->references('id')->on('tournaments')->nullable();
                }catch (\Illuminate\Database\QueryException $exception){
                    echo "the key tournament_id existed";
                } catch (\Exception $e) {
                    // something went wrong elsewhere, handle gracefully
                    echo "the key game_id existed but there was some other error";
                }
            });
        }

        /**
         * Add foreign keys for the individual players table to game
         */
        if (!Schema::connection('mysql_champ')->hasColumn('individual_players','game_id')) {
            Schema::connection('mysql_champ')->table('individual_players', function (Blueprint $table) {
                try {
                    $table->integer('game_id')->unsigned()->nullable();
                    $table->foreign('game_id')->references('id')->on('games')->nullable();
                }catch (\Illuminate\Database\QueryException $exception){
                    echo "the key game_id existed";
                } catch (\Exception $e) {
                    // something went wrong elsewhere, handle gracefully
                    echo "the key game_id existed but there was some other error";
                }
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
        if (Schema::connection('mysql_champ')->hasColumn('individual_players', 'game_id')) {
            Schema::connection('mysql_champ')->table('individual_players', function (Blueprint $table) {
                try {
                    $table->dropForeign('individual_players_game_id_foreign');
                    $table->dropColumn(['game_id']);
                }catch (\Illuminate\Database\QueryException $exception){
                    echo "the key individual_players_game_id_foreign did not existed";
                } catch (\Exception $e) {
                    // something went wrong elsewhere, handle gracefully
                    echo "the key game_id existed but there was some other error";
                }
            });
        }
        if (Schema::connection('mysql_champ')->hasColumn('players', 'team_id')) {
            Schema::connection('mysql_champ')->table('players', function (Blueprint $table) {
                try {
                    $table->dropForeign('players_team_id_foreign');
                    $table->dropColumn(['team_id']);
                }catch (\Illuminate\Database\QueryException $exception){
                    echo "the key players_team_id_foreign did not existed";
                } catch (\Exception $e) {
                    // something went wrong elsewhere, handle gracefully
                    echo "the key game_id existed but there was some other error";
                }
            });
        }
        if (Schema::connection('mysql_champ')->hasColumn('teams', 'tournament_id')) {
            Schema::connection('mysql_champ')->table('teams', function (Blueprint $table) {
                try {
                    $table->dropForeign('teams_tournament_id_foreign');
                    $table->dropColumn(['tournament_id']);
                }catch (\Illuminate\Database\QueryException $exception){
                    echo "the key teams_tournament_id_foreign did not existed";
                } catch (\Exception $e) {
                    // something went wrong elsewhere, handle gracefully
                    echo "the key game_id existed but there was some other error";
                }
            });
        }
        if (Schema::connection('mysql_champ')->hasColumn('tournaments', 'game_id')) {
            Schema::connection('mysql_champ')->table('tournaments', function (Blueprint $table) {
                try {
                    $table->dropForeign('tournaments_game_id_foreign');
                    $table->dropColumn(['game_id']);
                }catch (\Illuminate\Database\QueryException $exception){
                    echo "the key tournaments_game_id_foreign did not existed";
                } catch (\Exception $e) {
                    // something went wrong elsewhere, handle gracefully
                    echo "the key game_id existed but there was some other error";
                }
            });
        }
    }
}
