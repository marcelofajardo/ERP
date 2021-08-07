<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vendor_id')->unsigned();
            $table->datetime('date_of_order');
            $table->string('name');
            $table->integer('qty')->unsigned()->default(0);
            $table->string('price')->nullable()->default(0);
            $table->text('payment_terms')->nullable();
            $table->datetime('delivery_date')->nullable();
            $table->string('received_by')->nullable();
            $table->string('approved_by')->nullable();
            $table->text('payment_details')->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_products');
    }
}
