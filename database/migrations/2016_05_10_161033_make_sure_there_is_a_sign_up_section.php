<?php

use Illuminate\Database\Migrations\Migration;

class MakeSureThereIsASignUpSection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $page = DB::table('wp_posts')
            ->where('post_name', 'sign-up')
            ->where('post_type', 'page')
            ->first();
        if (!$page) {
            $inc = DB::select('SHOW TABLE STATUS FROM `' . env('DB_DATABASE') . '` LIKE \'wp_posts\' ;');

            DB::insert(
                'INSERT INTO `wp_posts` (`post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`)
                  VALUES
                  (1, \'' . date("Y-m-d H:i:s") . '\', \'' . date("Y-m-d H:i:s") . '\', \'If you&#39;re looking to participate in the [bloginfo key="description"] you can sign up as either <a href="http://gigazonegaming.local/sign-up/lol-team-sign-up/">a team</a> or <a href="http://gigazonegaming.local/sign-up/lol-individual-sign-up/">an individual player</a>.\', \'Sign Up\', \'\', \'publish\', \'closed\', \'closed\', \'\', \'sign-up\', \'\', \'\', \'' . date("Y-m-d H:i:s") . '\', \'' . date("Y-m-d H:i:s") . '\', \'\', 0, \'http://gigazonegaming.local/?page_id=' . $inc[0]->Auto_increment . '\', 0, \'page\', \'\', 0);'
            );
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /** @todo do not delete because it will screw up the navigation, maybe update via Wordpress rest API? */
    }
}
