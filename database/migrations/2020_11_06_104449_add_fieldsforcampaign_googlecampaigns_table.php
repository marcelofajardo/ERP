<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsforcampaignGooglecampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('googlecampaigns', function (Blueprint $table) {
            $table->string('channel_type')->nullable()->after('budget_id');
            $table->string('channel_sub_type')->nullable()->after('channel_type');
            $table->string('bidding_strategy_type')->nullable()->after('channel_sub_type');
            $table->string('target_cpa_value')->nullable()->after('bidding_strategy_type');
            $table->string('target_roas_value')->nullable()->after('target_cpa_value');
            $table->string('maximize_clicks')->nullable()->after('target_roas_value');
            $table->string('ad_rotation')->nullable()->after('maximize_clicks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('googlecampaigns', function (Blueprint $table) {
            //
        });
    }
}
