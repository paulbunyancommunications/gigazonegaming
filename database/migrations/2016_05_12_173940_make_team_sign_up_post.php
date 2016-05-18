<?php

use Illuminate\Database\Migrations\Migration;

class MakeTeamSignUpPost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $post = DB::table('wp_posts')
            ->where('post_name', 'lol-team-sign-up')
            ->where('post_type', 'post')
            ->first();
        $parent = DB::table('wp_posts')
            ->where('post_name', 'sign-up')
            ->where('post_type', 'page')
            ->first();
        if (!$post) {
            $now = date("Y-m-d H:i:s");
            $query = 'INSERT INTO `wp_posts` (`post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`)
                      VALUES
	                  (1, \''.$now.'\', \''.$now.'\', \'[team-sign-up new_line="," delimiter="|" questions="tournament|hidden|gigazone-gaming-2016-league-of-legends,Team Name,update-recipient|hidden|yes,participate|hidden|yes,Team Captain,Team Captain LOL Summoner Name,Team Captain Email Address|email,Team Captain Phone|tel,Teammate One LOL Summoner Name,Teammate One Email Address|email,Teammate Two LOL Summoner Name,Teammate Two Email Address|email,Teammate Three LOL Summoner Name,Teammate Three Email Address|email,Teammate Four LOL Summoner Name,Teammate Four Email Address|email" inputs="team-captain|name,team-captain-email-address|email" headings="Team Info|team-name,Team Captain|team-captain,Team Members|teammate-one-lol-summoner-name"]Please fill out the form below to let us know that your are interested in participating in the event[/team-sign-up]\', \'League of Legends Team Sign Up\', \'\', \'publish\', \'open\', \'open\', \'\', \'lol-team-sign-up\', \'\', \'\', \''.$now.'\', \''.$now.'\', \'\', '.$parent->ID.', \'http://gigazonegaming.local/auto-draft/\', 0, \'post\', \'\', 0);';
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
