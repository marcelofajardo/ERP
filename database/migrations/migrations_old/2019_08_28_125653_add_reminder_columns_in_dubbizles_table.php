<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReminderColumnsInDubbizlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dubbizles', function (Blueprint $table) {
            $table->integer('frequency')->nullable();
            $table->text('reminder_message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dubbizles', function (Blueprint $table) {
            $table->dropColumn(['frequency', 'reminder_message']);
        });
    }
}
