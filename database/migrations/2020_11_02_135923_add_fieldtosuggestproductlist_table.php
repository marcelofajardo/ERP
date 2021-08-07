<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldtosuggestproductlistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suggested_product_lists', function (Blueprint $table) {
            $table->unsignedInteger('suggested_products_id')->after('id')->nullable();
            $table->foreign('suggested_products_id')->references('id')->on('suggested_products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suggested_product_lists', function (Blueprint $table) {
            //
        });
    }
}
