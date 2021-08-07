<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id');
            $table->enum('type', ['post', 'album', 'story'])->default('post');
            $table->longText('ig')->nullable();
            $table->text('caption')->nullable();
            $table->text('comment')->nullable();
            $table->enum('status', ['1', '2', '3'])->default('1');
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('posted_at')->nullable();
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
        Schema::dropIfExists('posts');
    }
}
