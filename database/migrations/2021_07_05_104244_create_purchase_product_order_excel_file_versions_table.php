<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseProductOrderExcelFileVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_product_order_excel_file_versions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('excel_id')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_version')->nullable();
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
        Schema::dropIfExists('purchase_product_order_excel_file_versions');
    }
}
