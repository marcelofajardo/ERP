<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePushNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_notifications', function (Blueprint $table) {
	        $table->increments('id');
	        $table->string('role')->nullable();
	        $table->string('message');
	        $table->string('user_id');
	        $table->string('sent_to')->nullable();
	        $table->string('model_type');
	        $table->string('model_id');
	        $table->boolean('isread')->default(0);
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
        Schema::dropIfExists('push_notifications');
    }
}
