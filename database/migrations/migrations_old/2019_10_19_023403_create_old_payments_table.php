<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOldPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('old_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('old_id');
            $table->string('currency');
            $table->date('payment_date');
            $table->date('paid_date');
            $table->string('pending_amount');
            $table->string('paid_amount');
            $table->string('service_provided');
            $table->string('module');
            $table->string('description');
            $table->string('work_hour');
            $table->string('other');
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
        Schema::dropIfExists('old_payments');
    }
}
