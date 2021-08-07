<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableScraperScreenshotHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('scraper_screenshot_histories',function(Blueprint $table) {
            $table->increments('id');   
            $table->string('scraper_name')->nullable(); 
            $table->integer('scraper_id')->index()->nullable();
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
        Schema::dropIfExists('scraper_screenshot_histories');
    }
}
