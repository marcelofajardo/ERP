<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBrandScraperResult extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('brand_scraper_results', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->integer('brand_id')->nullable()->index();
            $table->string('scraper_name');
            $table->integer('total_urls');
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
        Schema::dropIfExists('brand_scraper_results');
    }
}
