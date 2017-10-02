<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGreetingFieldToFormMailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('form_mail')) {
            Schema::table('form_mail', function (Blueprint $table) {
                $table->addColumn('text', 'greeting');
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
        if (Schema::hasTable('form_mail')) {
            Schema::table('form_mail', function (Blueprint $table) {
                $table->dropColumn('greeting');
            });
        }
    }
}
