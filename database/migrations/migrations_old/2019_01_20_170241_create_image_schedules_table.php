<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImageSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('image_id')->usigned();
            $table->foreign('image_id')->references('id')->on('images');
            $table->text('description')->nullable();
            $table->dateTime('scheduled_for')->nullable();
            $table->boolean('facebook');
            $table->boolean('instagram');
            $table->boolean('status');
            $table->string('facebook_post_id')->nullable();
            $table->string('instagram_post_id')->nullable();
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
        Schema::dropIfExists('image_schedules');
    }
}
