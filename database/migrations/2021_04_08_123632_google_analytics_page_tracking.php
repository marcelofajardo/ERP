<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GoogleAnalyticsPageTracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_analytics_page_tracking', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('website_analytics_id')->nullable();
            $table->string('page')->nullable();
            $table->string('avg_time_page')->nullable();
            $table->string('page_views')->nullable();
            $table->string('unique_page_views')->nullable();
            $table->string('exit_rate')->nullable();
            $table->string('entrances')->nullable();
            $table->string('entrance_rate')->nullable();
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
        Schema::dropIfExists('google_analytics_page_tracking');
    }
}
