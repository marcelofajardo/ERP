<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToDailyActivities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_activities', function (Blueprint $table) {
          $table->integer('pending_for')->default(0)->after('for_date');
          $table->datetime('is_completed')->nullable()->after('pending_for');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daily_activities', function (Blueprint $table) {
          $table->dropColumn('pending_for');
          $table->dropColumn('is_completed');
        });
    }
}
