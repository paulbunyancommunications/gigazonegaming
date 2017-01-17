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
        $query = "INSERT INTO `champ_games` (`name`, `description`, `uri`, `created_at`, `updated_at`)
            VALUES ('unknown', 'Unknown game', '', NULL, NULL);";
        DB::connection('mysql_champ')->insert($query);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection('mysql_champ')->delete("DELETE FROM `champ_games` WHERE `name`='unknown'");
    }
}
