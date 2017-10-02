<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveLaravelUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users')) {
            Schema::drop('users');
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('users')) {
            DB::query(
                'CREATE TABLE `users` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
                  `created_at` timestamp NULL DEFAULT NULL,
                  `updated_at` timestamp NULL DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `users_email_unique` (`email`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;'
            );
        }
    }
}
