<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePushFcmNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_fcm_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token')->nullable();
            $table->string('title')->nullable(); 
            $table->text('body')->nullable();
            $table->string('url')->nullable(); 
            $table->integer('store_website_id');
            $table->foreign('store_website_id')->references('id')->on('store_websites');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('sent_on')->nullable();  
            $table->string('created_by')->nullable();
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
        Schema::dropIfExists('push_fcm_notifications');
    }
}
