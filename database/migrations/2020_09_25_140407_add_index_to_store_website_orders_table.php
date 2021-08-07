<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToStoreWebsiteOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_website_orders', function (Blueprint $table) {
            $table->index('website_id');
			$table->index('status_id');
			$table->index('order_id');
			$table->index('platform_order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_website_orders', function (Blueprint $table) {
            $table->dropIndex('website_id');
			$table->dropIndex('status_id');
			$table->dropIndex('order_id');
			$table->dropIndex('platform_order_id');
        });
    }
}
