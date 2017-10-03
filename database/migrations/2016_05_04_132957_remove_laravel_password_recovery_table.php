<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveLaravelPasswordRecoveryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('password_resets')) {
            Schema::drop('password_resets');
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('password_resets')) {
            DB::query(
                'CREATE TABLE `password_resets` (
                  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  KEY `password_resets_email_index` (`email`),
                  KEY `password_resets_token_index` (`token`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;'
            );
        }
    }
}
