<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuggestedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suggested_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('brands')->nullable();
            $table->string('categories')->nullable();
            $table->string('keyword')->nullable();
            $table->string('color')->nullable();
            $table->string('supplier')->nullable();
            $table->string('location')->nullable();
            $table->string('size')->nullable();
            $table->integer('total');
            $table->integer('customer_id');
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
        Schema::dropIfExists('suggested_products');
    }
}
