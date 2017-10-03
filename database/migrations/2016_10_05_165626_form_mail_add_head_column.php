<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FormMailAddHeadColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('form_mail', 'head')) {
            Schema::table('form_mail', function (Blueprint $table) {
                $table->addColumn('text', 'head');
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
        if (Schema::hasColumn('form_mail', 'head')) {
        Schema::table('form_mail', function (Blueprint $table) {
            $table->dropColumn('head');
        });
        }
    }
}
