<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLearningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learnings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category')->nullable();
            $table->integer('assign_from');
            $table->integer('assign_to');
            $table->integer('status')->nullable();
            $table->integer('assign_status')->nullable();
            $table->integer('is_statutory');
            $table->tinyInteger('is_private');
            $table->tinyInteger('is_watched');
            $table->tinyInteger('is_flagged');
            $table->longText('task_details');
            $table->longText('task_subject');
            $table->dateTime('completion_date');
            $table->longText('remark')->nullable();
            $table->dateTime('actual_start_date');
            $table->dateTime('is_completed');
            $table->integer('general_category_id')->nullable();
            $table->dateTime('is_verified')->nullable();
            $table->dateTime('sending_time')->nullable();
            $table->longText('time_slot')->nullable();
            $table->integer('planned_at')->nullable();
            $table->integer('pending_for');
            $table->longText('recurring_type')->nullable();
            $table->integer('statutory_id')->nullable();
            $table->longText('model_type')->nullable();
            $table->integer('model_id')->nullable();
            $table->dateTime('deleted_at');
            $table->integer('approximate');
            $table->integer('hubstaff_task_id');
            $table->decimal('cost')->nullable();
            $table->tinyInteger('is_milestone');
            $table->integer('no_of_milestone')->nullable();
            $table->integer('milestone_completed')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('master_user_id')->nullable();
            $table->integer('lead_hubstaff_task_id')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->integer('site_developement_id')->nullable();
            $table->integer('priority_no')->nullable();
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
        Schema::dropIfExists('learnings');
    }
}
