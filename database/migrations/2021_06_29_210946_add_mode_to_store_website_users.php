<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModeToStoreWebsiteUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('store_website_users',function(Blueprint $table) {
            $table->string('website_mode')->default('production')->nullable()->after('store_website_id')->comment('production,staging');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_website_users',function(Blueprint $table) {
            $table->dropField('website_mode');
        });
    }
}
