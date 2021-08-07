<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnExchangeProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_exchange_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('return_exchange_id')->nullable()->index();
            $table->integer('status_id')->nullable()->index();
            $table->integer('product_id');
            $table->text('order_product_id')->nullable();
            $table->text('name')->nullable();
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
        Schema::dropIfExists('return_exchange_products');
    }
}
