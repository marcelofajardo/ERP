<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHubstaffTaskEfficiencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hubstaff_task_efficiency', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0); // " hubstaff_members hubstaff_user_id"
            $table->string('admin_input',255)->nullable();
            $table->string('user_input',255)->nullable();
            $table->date('date')->nullable();
            $table->integer('time')->nullable();
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
        Schema::dropIfExists('hubstaff_task_efficiency');
    }
}
