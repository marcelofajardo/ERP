<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableAddPaymentDueDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('payment_receipts',function(Blueprint $table) {
            $table->datetime('billing_due_date')->nullable()->after('billing_end_date');
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
        Schema::table('payment_receipts',function(Blueprint $table) {
            $table->dropField('billing_due_date');
        });
    }
}
