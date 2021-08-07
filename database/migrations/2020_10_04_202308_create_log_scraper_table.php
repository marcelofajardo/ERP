<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogScraperTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('log_scraper')) {
            Schema::create('log_scraper', function (Blueprint $table) {
                $table->increments('id');
                $table->String('ip_address');
                $table->String('website');
                $table->String('url');
                $table->String('sku');
                $table->text('original_sku');
                $table->String('brand');
                $table->String('category');
                $table->String('title');
                $table->text('description');
                $table->text('properties');
                $table->text('images');
                $table->String('size_system');
                $table->String('currency');
                $table->String('price');
                $table->String('discounted_price');
                $table->String('is_sale');
                $table->tinyInteger('validated');
                $table->text('validation_result');
                $table->text('raw_data');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('log_scrapers');
    }
}
