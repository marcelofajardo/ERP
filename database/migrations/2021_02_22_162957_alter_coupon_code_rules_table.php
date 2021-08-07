<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCouponCodeRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupon_code_rules', function (Blueprint $table) {
            $table->integer('magento_rule_id')->nullable();
            $table->string('stop_rules_processing')->nullable();
            $table->string('simple_action')->nullable();
            $table->string('discount_amount')->nullable();
            $table->string('discount_step')->nullable();
            $table->string('discount_qty')->nullable();
            $table->string('apply_to_shipping')->nullable();
            $table->string('simple_free_shipping')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupon_code_rules', function (Blueprint $table) {

        });
    }
}
