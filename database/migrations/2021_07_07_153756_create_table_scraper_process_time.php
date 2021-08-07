<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableScraperProcessTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('scraper_processes',function(Blueprint $table){
            $table->increments('id');
            $table->integer("scraper_id");
            $table->string("server_id");
            $table->timestamp("started_at")->nullable();
            $table->timestamp("ended_at")->nullable();
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
        Schema::dropIfExists('scraper_processes');
    }
}
