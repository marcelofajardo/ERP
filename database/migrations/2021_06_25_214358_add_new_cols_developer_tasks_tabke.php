<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColsDeveloperTasksTabke extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('developer_tasks', function (Blueprint $table) {
            $table->integer('frequency')->default(0);
            $table->integer('reminder_last_reply')->default(1);
            $table->timestamp('reminder_from')->default("0000-00-00 00:00:00");
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
        Schema::table('developer_tasks', function (Blueprint $table) {
            $table->dropIfExists(['frequency', 'reminder_last_reply', 'reminder_from', 'reminder_message']);
        });
    }
}
