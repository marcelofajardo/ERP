<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToStoreWebsiteBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_website_brands', function (Blueprint $table) {
            $table->index(['brand_id','store_website_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_website_brands', function (Blueprint $table) {
            $table->dropIndex(['brand_id','store_website_id']);

        });
    }
}
