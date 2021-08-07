<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacebookMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facebook_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id');
            $table->string('sender');
            $table->string('receiver');
            $table->text('message');
            $table->boolean('is_sent_by_me')->default(1);
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
        Schema::dropIfExists('facebook_messages');
    }
}
