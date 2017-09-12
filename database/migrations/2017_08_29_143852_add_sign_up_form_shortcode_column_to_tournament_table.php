<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSignUpFormShortcodeColumnToTournamentTable extends Migration
{

    protected $column = 'sign_up_form_shortcode';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql_champ')->hasColumn('tournaments', $this->column)) {
            Schema::connection('mysql_champ')->table('tournaments', function (Blueprint $table) {
                $table->longText($this->column);
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
        if (Schema::connection('mysql_champ')->hasColumn('tournaments',$this->column)) {
            Schema::connection('mysql_champ')->table('tournaments', function (Blueprint $table) {
                $table->dropColumn($this->column);
            });
        }
    }
}
