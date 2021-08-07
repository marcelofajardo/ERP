<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableReturnRefundStatusMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('return_exchange_statuses', function (Blueprint $table) {
            $table->text('message')->nullable()->after('status_name');
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
        Schema::table('return_exchange_statuses', function (Blueprint $table) {
            $table->dropField('message');
        });
    }
}
