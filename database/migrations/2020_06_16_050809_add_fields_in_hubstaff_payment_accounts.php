<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddFieldsInHubstaffPaymentAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hubstaff_payment_accounts', function ($table) {
            $table->datetime("billing_start")->after('accounted_at');
            $table->datetime("billing_end")->after('billing_start');
            $table->float("hrs")->default("0.00")->after('billing_end');
            $table->float("rate")->default("0.00")->after('hrs');
            $table->char("currency")->default("USD")->after('rate');
            $table->char("payment_currency")->default("INR")->after('currency');
            $table->float("total_payout")->default("0.00")->after('payment_currency');
            $table->float("total_paid")->default("0.00")->after('total_payout');
            $table->float("ex_rate")->default("0.00")->after('payment_currency');
            $table->integer("status")->default(1)->after('ex_rate');
            $table->string("payment_info")->nullable()->after('status');
            $table->text("payment_remark")->nullable()->after('payment_info');
            $table->datetime("scheduled_on")->after('payment_remark');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hubstaff_payment_accounts', function ($table) {
            $table->dropColumn('billing_start');
            $table->dropColumn('billing_end');
            $table->dropColumn('hrs');
            $table->dropColumn('rate');
            $table->dropColumn('currency');
            $table->dropColumn('payment_currency');
            $table->dropColumn('total_payout');
            $table->dropColumn('total_paid');
            $table->dropColumn('ex_rate');
            $table->dropColumn('status');
            $table->dropColumn('payment_info');
            $table->dropColumn('payment_remark');
            $table->dropColumn('scheduled_on');
        });
    }
}
