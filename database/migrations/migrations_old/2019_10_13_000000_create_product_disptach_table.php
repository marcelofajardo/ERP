<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductDisptachTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_dispatch', function (Blueprint $table) {
            $table->increments('id');
            $table->string('modeof_shipment')->nullable();
            $table->string('awb')->nullable();
            $table->string('eta')->nullable();
            $table->dateTime('date_time');
            $table->integer('product_id')->nullable()->unsigned()->index();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->integer('created_by')->nullable()->unsigned()->index();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('product_dispatch');
    }
}
