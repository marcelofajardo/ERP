<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutoCommentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_comment_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('target');
            $table->string('post_code');
            $table->string('post_id');
            $table->integer('account_id');
            $table->integer('auto_reply_hashtag_id');
            $table->text('comment');
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
        Schema::dropIfExists('auto_comment_histories');
    }
}
