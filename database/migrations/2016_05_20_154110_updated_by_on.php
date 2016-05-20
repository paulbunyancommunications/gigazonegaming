<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatedByOn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_champ')->table('players', function (Blueprint $table) {
            $table->string('updated_by');
            $table->timestamp('updated_on');
        });
        Schema::connection('mysql_champ')->table('teams', function (Blueprint $table) {
            $table->string('updated_by');
            $table->timestamp('updated_on');
        });
        Schema::connection('mysql_champ')->table('games', function (Blueprint $table) {
            $table->string('updated_by');
            $table->timestamp('updated_on');
        });
        Schema::connection('mysql_champ')->table('tournaments', function (Blueprint $table) {
            $table->string('updated_by');
            $table->timestamp('updated_on');
        });
        Schema::connection('mysql_champ')->table('individual_players', function (Blueprint $table) {
            $table->string('updated_by');
            $table->timestamp('updated_on');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql_champ')->table('players', function (Blueprint $table) {
            $table->dropColumn('updated_by');
            $table->dropColumn('updated_on');
        });
        Schema::connection('mysql_champ')->table('teams', function (Blueprint $table) {
            $table->dropColumn('updated_by');
            $table->dropColumn('updated_on');
        });
        Schema::connection('mysql_champ')->table('games', function (Blueprint $table) {
            $table->dropColumn('updated_by');
            $table->dropColumn('updated_on');
        });
        Schema::connection('mysql_champ')->table('tournaments', function (Blueprint $table) {
            $table->dropColumn('updated_by');
            $table->dropColumn('updated_on');
        });
        Schema::connection('mysql_champ')->table('individual_players', function (Blueprint $table) {
            $table->dropColumn('updated_by');
            $table->dropColumn('updated_on');
        });
    }
}
