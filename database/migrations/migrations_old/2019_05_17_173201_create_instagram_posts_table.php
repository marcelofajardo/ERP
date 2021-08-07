<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstagramPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instagram_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('post_id');
            $table->string('user_id');
            $table->string('username');
            $table->longText('caption');
            $table->string('media_type');
            $table->text('media_url');
            $table->dateTime('posted_at');
            $table->string('source', 100)->default('hashtag');
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
        Schema::dropIfExists('instagram_posts');
    }
}
