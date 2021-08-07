<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAffiliateTableInfluencerPage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('affiliates',function(Blueprint $table) {
            $table->enum('type', ['affiliate', 'influencer'])->default('affiliate')->after('country');
            $table->string('facebook_followers')->nullable()->after('facebook');
            $table->string('instagram_followers')->nullable()->after('instagram');
            $table->string('twitter_followers')->nullable()->after('twitter');
            $table->string('youtube_followers')->nullable()->after('youtube');
            $table->string('linkedin_followers')->nullable()->after('linkedin');
            $table->string('pinterest_followers')->nullable()->after('pinterest');
            $table->text('worked_on')->nullable()->after('page_views_per_month');
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
        Schema::table('affiliates',function(Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('facebook_followers');
            $table->dropColumn('instagram_followers');
            $table->dropColumn('twitter_followers');
            $table->dropColumn('youtube_followers');
            $table->dropColumn('linkedin_followers');
            $table->dropColumn('pinterest_followers');
            $table->dropColumn('worked_on');
        });
    }
}
