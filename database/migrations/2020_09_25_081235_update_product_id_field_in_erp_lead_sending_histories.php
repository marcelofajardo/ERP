<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProductIdFieldInErpLeadSendingHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('erp_lead_sending_histories', function (Blueprint $table) {
            //$table->bigInteger('product_id')->charset(null)->nullable()->change();
            DB::statement('ALTER TABLE erp_lead_sending_histories MODIFY product_id  INTEGER;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('erp_lead_sending_histories', function (Blueprint $table) {
            //$table->dropColumn('product_id');
            DB::statement('ALTER TABLE erp_lead_sending_histories MODIFY product_id STRING;');
        });
    }
}
