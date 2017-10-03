<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetPostsPerPageToSix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('wp_options') and Schema::hasColumn('wp_options', 'option_name')) {
            $setting = DB::table('wp_options')
                ->where('option_name', '=','posts_per_page')
                ->first();
            if ($setting && $setting->option_value != 6) {
                $option = \App\Models\WpOption::find($setting->option_id);
                $option->option_value = 6;
                $option->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('wp_options') and Schema::hasColumn('wp_options', 'option_name')) {
            $setting = DB::table('wp_options')
                ->where('option_name', '=','posts_per_page')
                ->first();
            if ($setting && $setting->option_value != 10) {
                $option = \App\Models\WpOption::find($setting->option_id);
                $option->option_value = 10;
                $option->save();
            }
        }
    }
}
