<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddFieldOrderIaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement('ALTER TABLE `purchase_product_orders` CHANGE `order_products_id` `order_products_id` VARCHAR(191) NULL DEFAULT NULL');
        DB::statement('ALTER TABLE `purchase_product_orders` ADD `order_products_order_id` VARCHAR(191) NULL DEFAULT NULL AFTER `updated_at`');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
