<?php

use Illuminate\Database\Migrations\Migration;

class MakeIndividualSignUpPost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $post = DB::table('wp_posts')
            ->where('post_name', 'lol-individual-sign-up')
            ->where('post_type', 'post')
            ->first();
        $parent = DB::table('wp_posts')
            ->where('post_name', 'sign-up')
            ->where('post_type', 'page')
            ->first();
        if (!$post) {
            $now = date("Y-m-d H:i:s");
            $query = '
            INSERT INTO `wp_posts` (`post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`)
VALUES
	(1, \''.$now.'\', \''.$now.'\', \'[individual-sign-up new_line="," delimiter="|" questions="update-recipient|hidden|yes,participate|hidden|yes,Your Name,Your LOL Summoner Name,Your Email Address|email,Your Phone|tel" inputs="your-name|name,your-email-address|email"]Please fill out the form below to let us know that your are interested in participating in the event but don&#39;t have a team to compete with . We will try and find you a team[/individual - sign - up]\', \'League of Legends Individual Sign Up\', \'\', \'publish\', \'open\', \'open\', \'\', \'lol-individual-sign-up\', \'\', \'\', \''.$now.'\', \''.$now.'\', \'\', '.$parent->ID.', \'http://gigazonegaming.local/auto-draft/\', 0, \'post\', \'\', 0);

            ';
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
        /** @todo do not delete because it will screw up the navigation, maybe update via Wordpress rest API? */
    }
}
