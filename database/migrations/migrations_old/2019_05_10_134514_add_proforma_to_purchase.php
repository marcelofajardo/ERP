<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProformaToPurchase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
          $table->string('transaction_id')->nullable()->after('whatsapp_number');
          $table->datetime('transaction_date')->nullable()->after('transaction_id');
          $table->string('transaction_amount')->nullable()->after('transaction_date');
          $table->string('shipper')->nullable()->after('bill_number');
          $table->string('shipment_status')->nullable()->after('shipper');
          $table->string('shipment_cost')->nullable()->after('shipment_status');
          $table->boolean('proforma_confirmed')->default(0)->after('shipment_cost');
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
          $table->dropColumn('transaction_id');
          $table->dropColumn('transaction_date');
          $table->dropColumn('transaction_amount');
          $table->dropColumn('shipper');
          $table->dropColumn('shipment_status');
          $table->dropColumn('shipment_cost');
          $table->dropColumn('proforma_confirmed');
        });
    }
}
