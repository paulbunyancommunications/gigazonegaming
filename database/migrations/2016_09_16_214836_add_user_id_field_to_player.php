<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdFieldToPlayer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::connection('mysql_champ')->hasColumn('players', 'user_id')) {
            Schema::connection('mysql_champ')->table('players', function (Blueprint $table) {
                $table->addColumn('integer', 'user_id');
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
        if (Schema::connection('mysql_champ')->hasColumn('players', 'user_id')) {
            Schema::connection('mysql_champ')->table('players', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }
    }
}
