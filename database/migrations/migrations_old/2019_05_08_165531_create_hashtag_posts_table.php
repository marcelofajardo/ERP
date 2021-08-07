<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHashtagPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hashtag_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->integer('hashtag_id')->unsigned();
            $table->longText('description');
            $table->text('image_url')->nullable();
            $table->text('post_url')->nullable();
            $table->dateTime('created_date')->nullable();
            $table->integer('likes')->default(0);
            $table->integer('number_comments')->default(0);
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
        Schema::dropIfExists('hashtag_posts');
    }
}
