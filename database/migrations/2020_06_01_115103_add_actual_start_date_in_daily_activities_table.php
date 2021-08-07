<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActualStartDateInDailyActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_activities',function($table){
            $table->timestamp('actual_start_date')->default("0000-00-00 00:00:00")->nullable()->after("pending_for");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daily_activities',function($table){
            $table->dropColumn('actual_start_date');
        });
    }
}
