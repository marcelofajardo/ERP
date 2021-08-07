<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseProductOrderLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_product_order_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_product_order_id')->nullable();
            $table->string('header_name')->nullable();
            $table->string('replace_from')->nullable();
            $table->string('replace_to')->nullable();
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
        Schema::dropIfExists('purchase_product_order_logs');
    }
}
