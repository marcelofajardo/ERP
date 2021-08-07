<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShipmentChangedCountToOrderProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_products', function (Blueprint $table) {
          $table->integer('reschedule_count')->default(0)->unsigned()->after('shipment_date');
          $table->integer('purchase_id')->unsigned()->nullable()->after('reschedule_count');
          $table->string('batch_number')->nullable()->after('purchase_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_products', function (Blueprint $table) {
          $table->dropColumn('reschedule_count');
          $table->dropColumn('purchase_id');
          $table->dropColumn('batch_number');
        });
    }
}
