<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_receipts', function (Blueprint $table) {
            $table->increments('id');
            $table->date('billing_start_date');
            $table->date('billing_end_date');
            $table->integer('worked_minutes')->nullable();
            $table->decimal('payment')->nullable();
            $table->string('status');
            $table->integer('task_id')->nullable();
            $table->integer('developer_task_id')->nullable();
            $table->decimal('rate_estimated');
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
        Schema::dropIfExists('payment_receipts');
    }
}
