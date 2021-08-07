<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWaybillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::table('waybills', function (Blueprint $table) {
            $table->integer('order_id')->nullable()->change();
        });*/
        \DB::statement("ALTER TABLE `waybills` CHANGE `order_id` `order_id` INT(11) NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
