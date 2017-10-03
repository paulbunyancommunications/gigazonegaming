<?php

use Illuminate\Database\Migrations\Migration;

class SwitchSignUpGrammarOnLolForms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('wp_options') and Schema::hasColumns('wp_options', ['lol-individual-sign-up','post'])) {
            // update the individual lol player sign up form if not already
            $individualPost = DB::table('wp_posts')
                ->where('post_name', 'lol-individual-sign-up')
                ->where('post_type', 'post')
                ->first();
            if ($individualPost) {
                DB::table('wp_posts')
                    ->where('id', '=', $individualPost->ID)
                    ->update([
                        'post_name' => str_replace('sign-up', 'signup', $individualPost->post_name),
                        'post_title' => str_replace('Sign Up', 'Signup', $individualPost->post_title),
                        'post_content' => str_replace('lol-individual-signup', 'lol-individual-sign-up', $individualPost->post_content),
                        'post_modified' => \Carbon\Carbon::now()->toDateTimeString(),
                        'post_modified_gmt' => \Carbon\Carbon::now()->toDateTimeString(),
                    ]);
            }
        }
        if (Schema::hasTable('wp_options') and Schema::hasColumns('wp_options', ['lol-team-sign-up','post'])) {
            // update the team lol sign up form if not already
            $teamPost = DB::table('wp_posts')
                ->where('post_name', 'lol-team-sign-up')
                ->where('post_type', 'post')
                ->first();
            if ($teamPost) {
                DB::table('wp_posts')
                    ->where('id', '=', $teamPost->ID)
                    ->update([
                        'post_name' => str_replace('sign-up', 'signup', $teamPost->post_name),
                        'post_title' => str_replace('Sign Up', 'Signup', $teamPost->post_title),
                        'post_content' => str_replace('lol-team-signup', 'lol-team-sign-up', $teamPost->post_content),
                        'post_modified' => \Carbon\Carbon::now()->toDateTimeString(),
                        'post_modified_gmt' => \Carbon\Carbon::now()->toDateTimeString(),
                    ]);
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
        if (Schema::hasTable('wp_options') and Schema::hasColumns('wp_options', ['lol-team-sign-up','post'])) {
            // roll back the individual lol player sign up form
            $individualPost = DB::table('wp_posts')
                ->where('post_name', 'lol-individual-signup')
                ->where('post_type', 'post')
                ->first();
            if ($individualPost) {
                DB::table('wp_posts')
                    ->where('id', '=', $individualPost->ID)
                    ->update([
                        'post_name' => str_replace('signup', 'sign-up', $individualPost->post_name),
                        'post_title' => str_replace('Signup', 'Sign Up', $individualPost->post_title),
                        'post_content' => str_replace('lol-individual-sign-up', 'lol-individual-signup', $individualPost->post_content),
                        'post_modified' => \Carbon\Carbon::now()->toDateTimeString(),
                        'post_modified_gmt' => \Carbon\Carbon::now()->toDateTimeString(),
                    ]);
            }
        }
        if (Schema::hasTable('wp_options') and Schema::hasColumns('wp_options', ['lol-team-signup','post'])) {
            // roll back the team lol sign up form
            $teamPost = DB::table('wp_posts')
                ->where('post_name', 'lol-team-signup')
                ->where('post_type', 'post')
                ->first();
            if ($teamPost) {
                DB::table('wp_posts')
                    ->where('id', '=', $teamPost->ID)
                    ->update([
                        'post_name' => str_replace('signup', 'sign-up', $teamPost->post_name),
                        'post_title' => str_replace('Signup', 'Sign Up', $teamPost->post_title),
                        'post_content' => str_replace('lol-team-sign-up', 'lol-team-signup', $teamPost->post_content),
                        'post_modified' => \Carbon\Carbon::now()->toDateTimeString(),
                        'post_modified_gmt' => \Carbon\Carbon::now()->toDateTimeString(),
                    ]);
            }
        }
    }
}
