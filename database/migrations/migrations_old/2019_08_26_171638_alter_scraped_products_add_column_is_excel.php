<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterScrapedProductsAddColumnIsExcel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scraped_products', function (Blueprint $table) {
            $table->tinyInteger('is_excel')->default('0')->after('website');
            $table->index('last_inventory_at');
        });

        Schema::table('scraped_products', function (Blueprint $table) {
            $table->index('is_excel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scraped_products', function ($table) {
            $table->dropIndex('scraped_products_is_excel_index');
            $table->dropIndex('scraped_products_last_inventory_at_index');
        });

        Schema::table('scraped_products', function ($table) {
            $table->dropColumn('is_excel');
        });
    }
}
