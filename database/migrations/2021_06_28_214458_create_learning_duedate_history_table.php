<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLearningDuedateHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learning_duedate_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('learning_id');
            $table->date('old_duedate')->nullable();
            $table->date('new_duedate')->nullable();
            $table->string('update_by');
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
        Schema::dropIfExists('learning_duedate_history');
    }
}
