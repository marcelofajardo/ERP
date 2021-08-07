<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoogleSearchAnalyticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_search_analytics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('site_id')->nullable();
            $table->double('clicks', 16, 2)->nullable();
            $table->double('impressions', 8, 2)->nullable();
            $table->double('ctr', 8, 2)->nullable();
            $table->double('position', 8, 2)->nullable();
            $table->string('country')->nullable();
            $table->string('device')->nullable();
            $table->string('page')->nullable();
            $table->string('query')->nullable();
            $table->string('search_apperiance')->nullable();
          

            $table->foreign('site_id')
          ->references('id')->on('sites')
          ->onDelete('CASCADE')
          ->onUpdate('SET NULL');
            

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
        Schema::dropIfExists('google_search_analytics');

    }
}
