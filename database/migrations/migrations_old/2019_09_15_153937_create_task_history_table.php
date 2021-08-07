<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks_history', function (Blueprint $table) {
            $table->increments('id');
			$table->dateTime('date_time');
			$table->integer('task_id');
			$table->integer('user_id');
			$table->integer('old_assignee');
			$table->integer('new_assignee');
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
        Schema::dropIfExists('tasks_history');
    }
}
