<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToProductSuppliers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_suppliers', function (Blueprint $table) {
          $table->integer('stock')->unsigned()->default(0)->after('supplier_id');
          $table->string('price')->nullable()->after('stock');
          $table->string('size')->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_suppliers', function (Blueprint $table) {
          $table->dropColumn('stock');
          $table->dropColumn('price');
          $table->dropColumn('size');
        });
    }
}
