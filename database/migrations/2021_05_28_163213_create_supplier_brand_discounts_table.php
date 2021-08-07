<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierBrandDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_brand_discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('supplier_id')->nullable();
            $table->integer('brand_id')->nullable();
            $table->string('gender')->nullable();
            $table->string('category')->nullable(); 
            $table->string('generic_price')->nullable(); 
            $table->string('exceptions')->nullable(); 
            $table->string('condition_from_retail')->nullable(); 
            $table->string('condition_from_retail_exceptions')->nullable(); 
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
        Schema::dropIfExists('supplier_brand_discounts');
    }
}
