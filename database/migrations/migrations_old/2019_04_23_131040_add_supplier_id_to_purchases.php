<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSupplierIdToPurchases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
          $table->integer('supplier_id')->unsigned()->nullable()->after('purchase_handler');
          $table->integer('agent_id')->unsigned()->nullable()->after('supplier_id');

          $table->foreign('supplier_id')->references('id')->on('suppliers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
          $table->dropForeign(['supplier_id']);
          $table->dropColumn('supplier_id');
          $table->dropColumn('agent_id');
        });
    }
}
