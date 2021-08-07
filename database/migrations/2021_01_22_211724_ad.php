<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Ad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads',function(Blueprint $table) {
            $table->increments('id');
            $table->integer('campaign_id');
            $table->integer('adgroup_id');
            $table->text('finalurl');
            $table->text('displayurl');
            $table->text('headlines');
            $table->text('descriptions');
            $table->text('tracking_tamplate');
            $table->text('final_url_suffix');
            $table->text('customparam');
            $table->integer('different_url_mobile');
            $table->text('mobile_final_url');
            $table->string('ad_id');
            $table->text('ad_response');
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
        Schema::dropIfExists('ads');
    }
}
