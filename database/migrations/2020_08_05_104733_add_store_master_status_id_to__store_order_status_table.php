<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStoreMasterStatusIdToStoreOrderStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_order_statuses', function (Blueprint $table) {
            $table->integer('store_master_status_id');
            //$table->string('status')->nullable()->change();
        });
        \DB::statement("ALTER TABLE `store_order_statuses` CHANGE `status` `status` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_order_statuses', function (Blueprint $table) {
            //
        });
    }
}
