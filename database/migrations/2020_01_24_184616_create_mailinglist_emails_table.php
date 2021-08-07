<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailinglistEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailinglist_emails', function (Blueprint $table) {
           $table->increments('id');
           $table->integer('mailinglist_id');
           $table->integer('template_id');
           $table->text('html')->nullable();
           $table->timestamp('scheduled_date');
           $table->string('subject')->nullable();
           $table->integer('progress')->default(0);
           $table->integer('total_emails_scheduled')->default(0);
           $table->integer('total_emails_sent')->default(0);
           $table->integer('total_emails_undelivered')->default(0);
           $table->integer('api_template_id');
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
        Schema::dropIfExists('mailinglist_emails');
    }
}
