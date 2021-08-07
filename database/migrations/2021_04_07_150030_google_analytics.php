<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GoogleAnalytics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        if (!Schema::hasTable('google_analytics')) {
            // Code to create table
            Schema::create('google_analytics', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('website_analytics_id')->nullable();
                $table->text('dimensions')->nullable();
                $table->text('dimensions_name')->nullable();
                $table->text('dimensions_value')->nullable();
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
        Schema::dropIfExists('google_analytics');
    }
}
