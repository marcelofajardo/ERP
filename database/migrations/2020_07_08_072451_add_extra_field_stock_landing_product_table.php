<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraFieldStockLandingProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('landing_page_products', function (Blueprint $table) {
            $table->string('stock_status')->nullable()->default(1)->after("shopify_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('landing_page_products', function (Blueprint $table) {
            $table->dropColumn('stock_status');
        });
    }
}
