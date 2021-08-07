<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHashtagPostHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hashtag_post_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('hashtag');
            $table->integer('account_id')->nullable();
            $table->integer('instagram_automated_message_id')->nullable();
            $table->string('post_id');
            $table->string('cursor');
            $table->date('post_date');
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
        Schema::dropIfExists('hashtag_post_histories');
    }
}
