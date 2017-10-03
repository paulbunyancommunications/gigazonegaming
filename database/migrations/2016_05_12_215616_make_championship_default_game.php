<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeChampionshipDefaultGame extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::connection('mysql_champ')->hasTable('games')) {
            if(! \App\Models\Championship\Game::where("name", "=", "unknown")->exists() ) {
                $query = "INSERT INTO `champ_games` (`name`, `description`, `uri`, `created_at`, `updated_at`)
            VALUES ('unknown', 'Unknown game', '', NULL, NULL);";
                DB::connection('mysql_champ')->insert($query);
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
        if (Schema::connection('mysql_champ')->hasTable('games')) {
            if( \App\Models\Championship\Game::where("name", "=", "unknown")->exists() ) {
                DB::connection('mysql_champ')->delete("DELETE FROM `champ_games` WHERE `name`='unknown'");
            }
        }
    }
}
