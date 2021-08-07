<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScrapRequestHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scrap_request_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('scraper_id');
            $table->string('date');
            $table->string('start_time');
            $table->string('end_time');
            $table->integer('request_sent')->default(0);
            $table->integer('request_failed')->default(0)->nullable();
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
        Schema::dropIfExists('scrap_request_histories');
    }
}
