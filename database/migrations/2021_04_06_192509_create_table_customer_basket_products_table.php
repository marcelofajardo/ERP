<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCustomerBasketProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_basket_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_basket_id');
            $table->integer('product_id')->nullable();
            $table->string('product_sku')->nullable();
            $table->string('product_name')->nullable();
            $table->decimal('product_price', 10, 0)->default(0);
            $table->string('product_currency')->nullable();
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
        Schema::dropIfExists('customer_basket_products');
    }
}
