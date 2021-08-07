<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyCashFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_cash_flows', function (Blueprint $table) {
            $table->increments('id');
            $table->string('received_from')->nullable();
            $table->string('paid_to')->nullable();
            $table->integer('expected')->nullable();
            $table->integer('received')->nullable();
            $table->datetime('date');
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
        Schema::dropIfExists('daily_cash_flows');
    }
}
