<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableStoreWebsiteSeoFormat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('store_website_seo_formats',function(Blueprint $table) {
            $table->increments('id');   
            $table->string("meta_title")->nullable();
            $table->text("meta_description")->nullable();
            $table->text("meta_keyword")->nullable();
            $table->integer('store_website_id');
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
        Schema::dropIfExists('store_website_seo_formats');
    }
}
