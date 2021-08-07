<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StoreWebsiteCategorySeosHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_website_category_seos_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_website_cate_seos_id')->nullable();
            $table->text('old_keywords')->nullable();
            $table->text('new_keywords')->nullable();
            $table->text('old_description')->nullable();
            $table->text('new_description')->nullable();
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('store_website_category_seos_histories');
    }
}
