<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShipmentPriceInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table("waybill_invoices", function (Blueprint $table) {
            $table->decimal('invoice_amount', 10, 0)->default(0)->nullable()->after('invoice_date');
            $table->string('invoice_currency')->nullable()->after('invoice_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table("waybill_invoices", function (Blueprint $table) {
            $table->dropField('invoice_amount');
            $table->dropField('invoice_currency');
        });
    }
}
