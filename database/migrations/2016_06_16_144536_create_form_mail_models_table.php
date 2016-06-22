<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormMailModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('form_mail', function (Blueprint $table) {
            $table->increments('id');
            
            // name of form
            $table->string('form');
            
            // resource name for views and language
            $table->string('resource');
            
            // email of creator
            $table->string('sender');
            
            // email of the recipient
            $table->string('recipient');
            
            // fields from form submission
            $table->text('fields');
            
            // body of message to send to recipient
            $table->text('message_to_recipient');
            
            // body of message to send to recipient
            $table->text('message_to_sender');
            
            // subject of message
            $table->string('subject');
            
            // flag to check if this message has been sent already tp recipient
            $table->boolean('message_sent_to_recipient');
            
            // flag to check if a confirmation message was sent to sender
            $table->boolean('confirmation_sent_to_sender');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql')->drop('form_mail');
    }
}
