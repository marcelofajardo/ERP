<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->double('initial_amount', 15, 8)->nullable()->index();
            $table->string('uuid')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('coupon_type')->nullable()->index();
            $table->tinyInteger('status')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('coupon_type');
            $table->dropColumn('status');
        });
    }
}
