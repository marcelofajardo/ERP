<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index('order_id');
			$table->index('customer_id');
			$table->index('order_status_id');
			$table->index('user_id');
			$table->index('coupon_id');
			$table->index('invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('order_id');
			$table->dropIndex('customer_id');
			$table->dropIndex('order_status_id');
			$table->dropIndex('user_id');
			$table->dropIndex('coupon_id');
			$table->dropIndex('invoice_id');
        });
    }
}
