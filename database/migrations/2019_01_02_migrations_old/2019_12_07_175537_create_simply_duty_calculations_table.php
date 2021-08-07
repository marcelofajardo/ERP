<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSimplyDutyCalculationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('simply_duty_calculations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hscode');
            $table->string('origin_country');
            $table->string('destination_country');
            $table->string('monetary_amount');
            $table->string('weight_per_monetary_amount')->nullable();
            $table->string('max_duty_rate')->nullable();
            $table->string('max_monetary_amount')->nullable();
            $table->string('max_weight_per_monetary_amount')->nullable();
            $table->string('min_duty_rate')->nullable();
            $table->string('min_monetary_amount')->nullable();
            $table->string('min_weight_per_monetary_amount')->nullable();
            $table->string('weight_type')->nullable();
            $table->string('duty_rate')->nullable();
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
        Schema::dropIfExists('simply_duty_calculations');
    }
}
