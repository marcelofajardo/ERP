<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHubstaffActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hubstaff_activities', function (Blueprint $table) {
            //
            $table->integer('id');
            $table->integer('user_id');
            $table->integer('task_id');
            $table->dateTimeTz('starts_at');
            $table->integer('tracked');
            $table->timestamps();
            
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hubstaff_activities');
    }
}
