<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropBillingDatesFromPaymentReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_receipts', function (Blueprint $table) {
            $table->dropColumn('billing_start_date');
            $table->dropColumn('billing_end_date');
            $table->date('date');
            //$table->integer('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_receipts', function (Blueprint $table) {
            //
        });
    }
}
