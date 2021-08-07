<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StoreViewsGtMetrix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_views_gt_metrix', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_view_id')->nullable();
            $table->string('test_id')->nullable();
            $table->string('status')->nullable();
            $table->string('error')->nullable();
            $table->string('report_url')->nullable();
            $table->text('website_url')->nullable();
            $table->integer('html_load_time')->nullable();
            $table->integer('html_bytes')->nullable();
            $table->integer('page_load_time')->nullable();
            $table->integer('page_bytes')->nullable();
            $table->integer('page_elements')->nullable();
            $table->integer('pagespeed_score')->nullable();
            $table->integer('yslow_score')->nullable();
            $table->string('resources')->nullable();
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
        Schema::dropIfExists('store_views_gt_metrix');
    }
}
