<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSupplierAddScrapedBrands extends Migration
{
    public function up()
    {
        Schema::table('suppliers', function(Blueprint $table)
        {
            $table->longText('scraped_brands')->nullable()->after("brands");
            $table->longText('scraped_brands_raw')->nullable()->after("brands");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('scraped_brands');
            $table->dropColumn('scraped_brands_raw');
        });
    }
}
