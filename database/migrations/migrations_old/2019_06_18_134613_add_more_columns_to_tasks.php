<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreColumnsToTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
          $table->string('time_slot')->nullable()->after('sending_time');
          $table->date('planned_at')->nullable()->after('time_slot');
          $table->integer('pending_for')->default(0)->after('planned_at');
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
          $table->dropColumn('time_slot');
          $table->dropColumn('planned_at');
          $table->dropColumn('pending_for');
        });
    }
}
