<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColsDailyActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_activities', function (Blueprint $table) {
            $table->enum('type',['event','learning'])->default('event');
            $table->integer('type_table_id')->nullable();
            $table->date('next_run_at')->nullable()->comment('if type learning for daily');
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
            $table->dropColumn('type');
            $table->dropColumn('next_run_at');
        });
    }
}
