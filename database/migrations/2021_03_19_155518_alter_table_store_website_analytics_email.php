<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableStoreWebsiteAnalyticsEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table("store_website_analytics",function(Blueprint $table) {
            $table->string("email")->nullable()->after("website");
            $table->string("last_error")->nullable()->after("email");
            $table->timestamp("last_error_at")->nullable()->after("last_error");
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
        Schema::table("store_website_analytics",function(Blueprint $table) {
            $table->dropField("email");
            $table->dropField("last_error");
            $table->dropField("last_error_at");
        });
    }
}
