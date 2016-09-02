<?php

use Illuminate\Database\Migrations\Migration;

class AddCommentToPlayerUsername extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ///* 7:56:27 AM vagrant gzgaming_champ_db */ ALTER TABLE `players` CHANGE `username` `username` VARCHAR(255)  CHARACTER SET utf8  COLLATE utf8_unicode_ci  NOT NULL  DEFAULT ''  COMMENT 'Used for game identity, such as summoner name for LOL';

        $query = "ALTER TABLE `players` CHANGE `username` `username` VARCHAR(255)  CHARACTER SET utf8  COLLATE utf8_unicode_ci  NOT NULL  DEFAULT ''  COMMENT 'Used for game identity, such as summoner name for LOL';";
        DB::connection('mysql_champ')->insert($query);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $query = "ALTER TABLE `players` CHANGE `username` `username` VARCHAR(255)  CHARACTER SET utf8  COLLATE utf8_unicode_ci  NOT NULL  DEFAULT ''  COMMENT '';";
        DB::connection('mysql_champ')->insert($query);

    }
}
