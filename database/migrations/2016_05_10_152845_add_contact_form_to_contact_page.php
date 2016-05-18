<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactFormToContactPage extends Migration
{
    protected $shortCode = '/(\\[)(contact)(-)(us)( ).*?(\\]).*?(\\[\\/contact-us\\])/is';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $page = DB::table('wp_posts')->where('post_name', 'contact-us')->first();
        $page = App\Models\WpPost::find($page->ID);
        $contentString = '[contact-us new_line="," delimiter="|" questions="Please list any comments or suggestions.|textarea,Your Name|text,Your Email Address|email,Sign up for updates|boolean" inputs="your-name|name,your-email-address|email,sign-up-for-updates|update-recipient,please-list-any-comments-or-suggestions|comment"]Have comments or questions, please let us know![/contact-us]';
        if ($page && !preg_match($this->shortCode, $page->post_content, $matches)) {
            $page->post_content = $page->post_content . $contentString;
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
        $page = DB::table('wp_posts')->where('post_name', 'contact-us')->first();
        $page = App\Models\WpPost::find($page->ID);
        if($page && preg_match($this->shortCode, $page->post_content, $matches)) {
            $page->post_content = preg_replace($this->shortCode, '', $page->post_content);
            $page->save();
        }
    }
}
