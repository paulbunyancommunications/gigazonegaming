<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTitleToTournamentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::connection('mysql_champ')->hasColumn('tournaments','title')) {
            Schema::connection('mysql_champ')->table('tournaments', function (Blueprint $table) {
                $table->string('title')
                    ->default("")
                    ->after('name');
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
        if (Schema::connection('mysql_champ')->hasColumn('tournaments', 'title')) {
            Schema::connection('mysql_champ')->table('tournaments', function (Blueprint $table) {
                $table->dropColumn('title');
            });
        }
    }
}
