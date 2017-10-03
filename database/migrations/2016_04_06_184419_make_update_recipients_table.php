<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeUpdateRecipientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('update_recipients')) {
            Schema::create('update_recipients', function (Blueprint $table) {
                $table->increments('id');
                $table->string('email');
                $table->boolean('participate')->default(0);
                $table->timestamps();
                $table->engine = 'InnoDB';
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('update_recipients')) {
            Schema::drop('update_recipients');
        }
    }
}
