<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesRoutinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities_routines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('action');
            $table->string('times_a_day')->default(0);
            $table->string('times_a_week')->default(0);
            $table->string('times_a_month')->default(0);
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
        Schema::dropIfExists('activities_routines');
    }
}
