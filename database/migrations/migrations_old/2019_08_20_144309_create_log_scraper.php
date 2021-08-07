<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogScraper extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_scraper', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('website')->nullable();;
            $table->string('url')->nullable();;
            $table->string('sku')->nullable();;
            $table->string('title')->nullable();;
            $table->text('description')->nullable();;
            $table->text('properties')->nullable();;
            $table->text('images')->nullable();;
            $table->string('size_system')->nullable();;
            $table->string('currency')->nullable();;
            $table->string('price')->nullable();;
            $table->string('discounted_price')->nullable();;
            $table->string('is_sale')->default('0');
            $table->tinyInteger('validated');
            $table->text('validation_result');
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
        Schema::dropIfExists('log_scraper');
    }
}
