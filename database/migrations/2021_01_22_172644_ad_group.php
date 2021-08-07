<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_groups',function(Blueprint $table) {
            $table->increments('id');
            $table->integer('campaign_id');
            $table->string('google_campaign_id');
            $table->string('type');
            $table->string('group_name');
            $table->string('url');
            $table->text('keywords');
            $table->string('budget',10,2);
            $table->string('google_ad_group_id');
            $table->text('google_ad_group_response');
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
        Schema::dropIfExists('ad_groups');
    }
}
