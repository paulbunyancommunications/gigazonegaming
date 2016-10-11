<?php

use Illuminate\Database\Seeder;

class WpTestAdminUserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create the tester user if not already created
        $user = \App\Models\WpUser::firstOrCreate([
            'user_login' => 'tester'
        ]);
        $user->user_pass = "123abc234def";
        $user->user_email = "tester@example.com";
        $user->save();

        // now attach the admin capabilities to that user if not already set
        $capabilities = \App\Models\WpUserMeta::firstOrCreate([
            'user_id' => $user->ID,
            'meta_key' => 'wp_capabilities'
        ]);

        $capabilities->meta_value = 'a:1:{s:13:"administrator";b:1;}';
        $capabilities->save();
    }
}
