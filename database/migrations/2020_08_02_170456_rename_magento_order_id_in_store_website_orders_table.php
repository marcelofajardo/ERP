<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameMagentoOrderIdInStoreWebsiteOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::table('store_website_orders', function (Blueprint $table) {
            $table->renameColumn('magento_order_id', 'platform_order_id');
        });*/
        \DB::statement("ALTER TABLE `store_website_orders` CHANGE `magento_order_id` `platform_order_id` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::table('store_website_orders', function (Blueprint $table) {
            $table->renameColumn('platform_order_id', 'magento_order_id');
        });*/
    }
}
