<?php

use Illuminate\Database\Migrations\Migration;

class MakeGigazoneServicePage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $post = DB::table('wp_posts')
            ->where('post_name', 'about-gigazone')
            ->where('post_type', 'post')
            ->first();
        if (!$post) {
            $parent = DB::table('wp_posts')
                ->where('post_name', 'about')
                ->where('post_type', 'page')
                ->first();
            $now = date("Y-m-d H:i:s");
            $query = "INSERT INTO `wp_posts` (`post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`)
            VALUES
            (1, '{$now}', '{$now}', '[gigazone-info]', 'About The Gigazone Service', '', 'publish', 'open', 'open', '', 'about-gigazone', '', '', '{$now}', '{$now}', '', {$parent->ID}, 'http://gigazonegaming.local/auto-draft/', 0, 'post', '', 0)";
            DB::insert($query);
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
