<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGigazoneInfoShortcodeToHomepageFooter extends Migration
{

    protected $shortCode = '[gigazone-info]';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $frontPage = DB::table('wp_options')->where('option_name', 'page_on_front')->first();
        $page = App\Models\WpPost::find($frontPage->option_value);
        if($page && strpos($page->post_content, $this->shortCode) === false) {
            $page->post_content = $page->post_content . "\n\r" . $this->shortCode;
            $page->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $frontPage = DB::table('wp_options')->where('option_name', 'page_on_front')->first();
        $page = App\Models\WpPost::find($frontPage->option_value);
        if($page && strpos($page->post_content, $this->shortCode) !== false) {
            $page->post_content = str_replace_last("\n\r" . $this->shortCode, '', $page->post_content);
            $page->save();
        }
    }
}
