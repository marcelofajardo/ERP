<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreWebsiteProductCheck extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_website_product_checks',function(Blueprint $table) {
            $table->increments('id');
            $table->integer('website_id')->nullable();
            $table->longText('website')->nullable();
            $table->longText('sku')->nullable();
            $table->longText('size')->nullable();
            $table->longText('brands')->nullable();
            $table->longText('dimensions')->nullable();
            $table->longText('composition')->nullable();
            $table->longText('images')->nullable();
            $table->longText('english')->nullable();
            $table->longText('arabic')->nullable();
            $table->longText('german')->nullable();
            $table->longText('spanish')->nullable();
            $table->longText('french')->nullable();
            $table->longText('italian')->nullable();
            $table->longText('japanese')->nullable();
            $table->longText('korean')->nullable();
            $table->longText('russian')->nullable();
            $table->longText('chinese')->nullable();

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
        Schema::dropIfExists('store_website_product_checks');
    }
}
