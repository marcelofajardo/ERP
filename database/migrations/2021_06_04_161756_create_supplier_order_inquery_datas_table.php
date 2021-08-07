<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierOrderInqueryDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_order_inquiry_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('supplier_id')->nullable();
            $table->string('product_id')->nullable();
            $table->string('type')->nullable();
            $table->integer('count_number');
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
        Schema::dropIfExists('supplier_order_inquiry_datas');
    }
}
