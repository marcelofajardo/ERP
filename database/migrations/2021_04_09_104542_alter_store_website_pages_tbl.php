<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStoreWebsitePagesTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_website_pages', function (Blueprint $table) {
            $table->longText("meta_keyword_avg_monthly")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_website_pages', function (Blueprint $table) {
            $table->dropField("meta_keyword_avg_monthly");
        });
    }
}
