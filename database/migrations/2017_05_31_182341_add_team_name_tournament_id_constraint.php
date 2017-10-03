<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTeamNameTournamentIdConstraint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::connection('mysql_champ')->hasColumns('teams',['name','tournament_id'])) {
            Schema::connection('mysql_champ')->table('teams', function (Blueprint $table) {
                try {
                    $table->unique(['name', 'tournament_id'])->unsigned()->nullable();
                }catch (\Illuminate\Database\QueryException $exception){
                    echo "the key already existed";
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
        if(Schema::connection('mysql_champ')->hasColumns('teams',['name','tournament_id'])) {
            Schema::connection('mysql_champ')->table('teams', function (Blueprint $table) {
                try {
                    $table->dropUnique('teams_name_tournament_id_unique');
                }catch (\Illuminate\Database\QueryException $exception){
                    echo "the key did not existed";
                } catch (\Exception $e) {
                    // something went wrong elsewhere, handle gracefully
                    echo "the key game_id existed but there was some other error";
                }
            });
        }
    }
}
