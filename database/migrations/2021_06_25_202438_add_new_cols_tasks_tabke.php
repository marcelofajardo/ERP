<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColsTasksTabke extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            if(!Schema::hasColumn('tasks','frequency')) {
                $table->integer('frequency')->default(0);
            }
            if(!Schema::hasColumn('tasks','reminder_last_reply')) {
                $table->integer('reminder_last_reply')->default(1);
            }
            if(!Schema::hasColumn('tasks','reminder_from')) {
                $table->timestamp('reminder_from')->default("0000-00-00 00:00:00");
            }
            if(!Schema::hasColumn('tasks','reminder_message')) {
                $table->text('reminder_message')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIfExists(['frequency', 'reminder_last_reply', 'reminder_from', 'reminder_message']);
        });
    }
}
