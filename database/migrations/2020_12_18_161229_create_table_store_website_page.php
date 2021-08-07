<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableStoreWebsitePage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('store_website_pages',function(Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('content_heading')->nullable();
            $table->text('content')->nullable();
            $table->string('layout')->nullable();
            $table->string('url_key')->nullable();
            $table->integer('active')->nullable()->default(0);
            $table->string('stores')->nullable();
            $table->string('platform_id')->nullable();
            $table->integer('store_website_id')->nullable();
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
        //
        Schema::dropIfExists('store_website_pages');
    }
}
