<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialStrategiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_strategies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('social_strategy_subject_id');
            $table->string('description');
            $table->integer('execution_id')->nullable();
            $table->integer('content_id')->nullable();
            $table->integer('website_id');
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
        Schema::dropIfExists('social_strategies');
    }
}
