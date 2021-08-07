<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHubstaffTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hubstaff_tasks', function (Blueprint $table) {
            //
            $table->increments('id');
            $table->integer('hubstaff_task_id');
            $table->integer('project_id');
            $table->integer('hubstaff_project_id');
            $table->string('summary');
            $table->softDeletes();
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
        Schema::drop('hubstaff_tasks');
    }
}
