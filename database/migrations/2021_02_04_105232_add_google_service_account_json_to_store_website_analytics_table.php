<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGoogleServiceAccountJsonToStoreWebsiteAnalyticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_website_analytics', function (Blueprint $table) {
            $table->string('google_service_account_json')->nullable()->after('store_website_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_website_analytics', function (Blueprint $table) {
            $table->dropColumn('google_service_account_json');
        });
    }
}
