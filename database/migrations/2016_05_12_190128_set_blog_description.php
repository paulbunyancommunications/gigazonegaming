<?php

use Illuminate\Database\Migrations\Migration;

class SetBlogDescription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = DB::table('wp_options')
            ->where('option_name', 'blogdescription')
            ->first();
        if ($setting && $setting->option_value === '') {
            $option = \App\Models\WpOption::find($setting->option_id);
            $option->option_value = 'Gigazone Gaming Championship';
            $option->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
