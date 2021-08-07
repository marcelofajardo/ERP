<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_activities', function (Blueprint $table) {

            $table->increments('id');

            $table->string('time_slot')->nullable();
	        $table->string('activity')->nullable();

            $table->integer('user_id');
            $table->integer('is_admin')->nullable();

            $table->string('assist_msg')->nullable();

            $table->date('for_date');
            $table->timestamps();
        });

        Schema::create('daily_task', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('assign_from');
            $table->integer('assign_to');
            $table->integer('is_statutory');
            $table->string('task_details');

            $table->date('completion_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_activities');
        Schema::dropIfExists('daily_task');
    }
}
