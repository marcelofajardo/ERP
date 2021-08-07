<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCustomerTableReminderFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->integer('reminder_last_reply')->default(1)->after("frequency");
            $table->timestamp('reminder_from')->default("0000-00-00 00:00:00")->after("reminder_last_reply");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('reminder_last_reply');
            $table->dropColumn('reminder_from');
        });
    }
}
