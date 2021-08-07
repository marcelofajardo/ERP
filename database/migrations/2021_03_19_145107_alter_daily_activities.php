<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDailyActivities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_activities', function (Blueprint $table) {
            $table->string('repeat_type')->nullable();
            $table->string('repeat_on')->nullable();
            $table->string('repeat_end')->nullable();
            $table->date('repeat_end_date')->nullable();
            $table->integer('parent_row')->nullable();
            $table->string('status')->nullable();
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
            $table->dropField('repeat_type');
            $table->dropField('repeat_on');
            $table->dropField('repeat_end');
            $table->dropField('repeat_end_date');
            $table->dropField('parent_row');
            $table->dropField('status');
        });
    }
}
