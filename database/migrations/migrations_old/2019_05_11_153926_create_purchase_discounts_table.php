<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_id');
            $table->integer('product_id')->unsigned();
            $table->string('percentage')->nullable();
            $table->string('amount')->nullable();
            $table->string('type');
            $table->timestamps();

            $table->foreign('purchase_id')->references('id')->on('purchases');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_discounts');
    }
}
