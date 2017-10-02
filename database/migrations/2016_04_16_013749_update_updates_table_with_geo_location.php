<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUpdatesTableWithGeoLocation extends Migration
{
    /**
     * Run the migrations. Add geo)lat and geo_long columns to store gwo location data
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('throttle')) {
        Schema::table('update_recipients', function(Blueprint $table) {
           $table->string('geo_lat');
           $table->string('geo_long');
        });
    }

    /**
     * Reverse the migrations. Drop the geo location columns
     *
     * @return void
     */
    public function down()
    {
        Schema::table('update_recipients', function(Blueprint $table) {
            $table->dropColumn('geo_lat');
            $table->dropColumn('geo_long');
        });
    }
}
