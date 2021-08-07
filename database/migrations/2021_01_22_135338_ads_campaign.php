<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdsCampaign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_campaigns',function(Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_account_id');
            $table->string('goal');
            $table->string('type');
            $table->string('campaign_name');
            $table->text('data');
            $table->string('campaign_budget_id');
            $table->string('campaign_id');
            $table->string('campaign_response');
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
        Schema::dropIfExists('ad_campaigns');
    }
}
