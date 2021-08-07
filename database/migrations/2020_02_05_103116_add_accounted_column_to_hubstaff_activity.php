<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountedColumnToHubstaffActivity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('hubstaff_activities', function (Blueprint $table) {
            $table->integer('hubstaff_payment_account_id')->nullable();
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
        Schema::table('hubstaff_activities', function (Blueprint $table) {
            $table->dropColumn('hubstaff_payment_account_id');
        });
    }
}
