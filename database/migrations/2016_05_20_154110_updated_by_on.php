<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdatedByOn extends Migration
{
    protected $tables = ['players', 'teams', 'games', 'tournaments'];
    protected $columns = ['updated_by', 'updated_on'];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        for ($t = 0; $t < count($this->tables); $t++) {
            if (!Schema::connection('mysql_champ')->hasTable($this->tables[$t])) {
                continue;
            }
            $columns = DB::connection('mysql_champ')->getSchemaBuilder()->getColumnListing($this->tables[$t]);
            Schema::connection('mysql_champ')->table($this->tables[$t], function (Blueprint $table) use ($columns) {
                for ($c = 0; $c < count($this->columns); $c++) {
                    if (!in_array($this->columns[$c], $columns)) {
                        $table->integer($this->columns[$c]);
                    }
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
        for ($t = 0; $t < count($this->tables); $t++) {
            $columns = DB::connection('mysql_champ')->getSchemaBuilder()->getColumnListing($this->tables[$t]);
            Schema::connection('mysql_champ')->table($this->tables[$t], function (Blueprint $table) use ($columns) {
                for ($c = 0; $c < count($this->columns); $c++) {
                    if (in_array($this->columns[$c], $columns)) {
                        $table->dropColumn($this->columns[$c]);
                    }
                }
            });
        }
    }
}
