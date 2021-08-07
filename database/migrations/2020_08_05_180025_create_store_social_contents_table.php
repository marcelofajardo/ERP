<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreSocialContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_social_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_social_content_category_id');
            $table->integer('store_website_id');
            $table->timestamp('request_date')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->timestamp('publish_date')->nullable();
            $table->string('platform')->nullable();
            $table->integer('store_social_content_status_id');
            $table->integer('creator_id')->nullable();
            $table->integer('publisher_id')->nullable();
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
        Schema::dropIfExists('store_social_contents');
    }
}
