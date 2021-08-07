<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstagramDirectMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instagram_direct_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('instagram_thread_id');
            $table->text('message');
            $table->integer('message_type');
            $table->string('sender_id');
            $table->string('receiver_id');
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('instagram_direct_messages');
    }
}
