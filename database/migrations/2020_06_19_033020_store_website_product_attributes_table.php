<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StoreWebsiteProductAttributesTable extends Migration
{
    /**
     * Run the migrations.
    - id
    - product_id
    - description
    - store_website_id
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_website_product_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->integer('store_website_id')->unsigned();
            $table->foreign('store_website_id')->references('id')->on('store_websites')->onDelete('cascade');
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
        Schema::dropIfExists('store_website_product_attributes');
    }
}
