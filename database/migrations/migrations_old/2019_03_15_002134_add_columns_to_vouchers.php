<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToVouchers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vouchers', function (Blueprint $table) {
          $table->integer('delivery_approval_id')->after('user_id')->nullable();
          $table->string('travel_type')->after('description')->nullable();
          $table->integer('paid')->after('amount')->nullable();

          $table->foreign('delivery_approval_id')->references('id')->on('delivery_approvals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vouchers', function (Blueprint $table) {
          $table->dropForeign(['delivery_approval_id']);
          $table->dropColumn('travel_type');
          $table->dropColumn('paid');
        });
    }
}
