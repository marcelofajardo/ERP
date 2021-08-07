<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseProductOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_product_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->nullable()->index();
            $table->integer('order_products_id')->nullable();
            $table->integer('order_id')->nullable()->index();
            $table->integer('supplier_id')->nullable()->index();
            $table->string('invoice')->nullable();
            $table->string('payment_currency')->nullable();
            $table->string('payment_amount')->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('shipping_cost')->nullable();
            $table->string('duty_cost')->nullable();
            $table->string('status')->nullable();
            $table->string('mrp_price')->nullable();
            $table->string('discount_price')->nullable();
            $table->string('special_price')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_product_orders');
    }
}
