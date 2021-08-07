<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstagramAutomatedMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instagram_automated_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->default('text');
            $table->string('sender_type')->default('normal');
            $table->string('receiver_type')->default('hashtag_posts');
            $table->text('message')->nullable();
            $table->text('attachments')->nullable();
            $table->integer('status')->default(0);
            $table->integer('reusable')->default(0);
            $table->integer('use_count')->default(0);
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
        Schema::dropIfExists('instagram_automated_messages');
    }
}
