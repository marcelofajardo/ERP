<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableScrapLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('scrap_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('scraper_id')->nullable();
            $table->string('folder_name')->nullable();
            $table->string('file_name')->nullable();
            $table->longText('log_messages')->nullable();
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
        Schema::dropIfExists('scrap_logs');
    }
}
