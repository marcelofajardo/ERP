<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaseCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_costs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('case_id')->unsigned()->nullable();
            $table->foreign('case_id')->references('id')->on('cases')->onDelete('cascade')->onUpdate('cascade');
            $table->date('billed_date')->nullable();
            $table->decimal('amount',13,4)->nullable();
            $table->date('paid_date')->nullable();
            $table->decimal('amount_paid',13,4)->nullable();
            $table->text('other')->nullable();
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
        Schema::dropIfExists('case_costs');
    }
}
