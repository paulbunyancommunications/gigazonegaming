<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCacheValueColumnTypeToLongText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('cache') and Schema::hasColumn('cache', "value")) {
            Schema::table('cache', function ($table) {
                $table->longText('value')->change();
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
        if (Schema::hasTable('cache') and Schema::hasColumn('cache', "value")) {
            Schema::table('cache', function ($table) {
                $table->text('value')->change();
            });
        }
    }
}
