<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeRiotDisclaimerPost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $post = \DB::table('wp_posts')->where('post_name', 'riot-disclaimer')->first();
        if (!$post) {
            $query = 'INSERT INTO `wp_posts` (`post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`)
VALUES
	(1, \''. date("Y-m-d H:i:s") .'\', \''. date("Y-m-d H:i:s") .'\', \'[bloginfo key=\"name\"] isn’t endorsed by Riot Games and doesn’t reflect the views or opinions of Riot Games or anyone officially involved in producing or managing League of Legends. League of Legends and Riot Games are trademarks or registered trademarks of Riot Games, Inc. League of Legends © Riot Games, Inc.\', \'Riot Disclaimer\', \'\', \'publish\', \'open\', \'open\', \'\', \'riot-disclaimer\', \'\', \'\', \''. date("Y-m-d H:i:s") .'\', \''. date("Y-m-d H:i:s") .'\', \'\', 0, \'http://gigazonegaming.local/auto-draft/\', 0, \'post\', \'\', 0);
';
            return \DB::statement($query);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('wp_posts')->where('post_name', 'riot-disclaimer')->delete();
    }
}
