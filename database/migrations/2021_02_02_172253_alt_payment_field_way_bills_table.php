<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AltPaymentFieldWayBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('waybills',function(Blueprint $table) {
            $table->timestamp('paid_date')->nullable()->after('pickuprequest');
            $table->string('payment_mode')->nullable()->after('paid_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('waybills',function(Blueprint $table) {
            $table->dropField('paid_date');
            $table->dropField('payment_mode');
        });
    }
}
