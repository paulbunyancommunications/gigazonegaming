<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomRequestBodyColumnToFormMailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('form_mail', 'custom_request_body')) {
            Schema::table('form_mail', function (Blueprint $table) {
                $table->longText('custom_request_body');
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
        if (Schema::hasColumn('form_mail', 'custom_request_body')) {
            Schema::table('form_mail', function (Blueprint $table) {
                $table->dropColumn('custom_request_body');
            });
        }
    }
}
