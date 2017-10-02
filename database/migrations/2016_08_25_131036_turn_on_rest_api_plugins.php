<?php

use Illuminate\Database\Migrations\Migration;

class TurnOnRestApiPlugins extends Migration
{

    protected $plugins = ['rest-api/plugin.php'];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('wp_options') and Schema::hasColumn('wp_options', 'option_name')) {
            $getOption = DB::table('wp_options')
                ->where('option_name', 'active_plugins')
                ->first();
            if ($getOption) {
                $option = \App\Models\WpOption::find($getOption->option_id);
                $list = unserialize($option->option_value);
                for ($i = 0; $i < count($this->plugins); $i++) {
                    if (!in_array($this->plugins[$i], $list)) {
                        array_push($list, $this->plugins[$i]);
                    }
                }
                $option->option_value = serialize(array_values($list));
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
            $getOption = DB::table('wp_options')
                ->where('option_name', 'active_plugins')
                ->first();
            if ($getOption) {
                $option = \App\Models\WpOption::find($getOption->option_id);
                $list = unserialize($option->option_value);
                for ($i = 0; $i < count($this->plugins); $i++) {
                    if (in_array($this->plugins[$i], $list)) {
                        $key = array_search($this->plugins[$i], $list);
                        if ($key !== false) {
                            unset($list[$key]);
                        }
                    }
                }
                $option->option_value = serialize(array_values($list));
                $option->save();
            }
        }
    }
}
