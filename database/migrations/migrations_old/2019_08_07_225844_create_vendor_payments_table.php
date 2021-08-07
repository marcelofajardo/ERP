<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('vendor_id')->unsigned();
            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->integer('currency')->default(0);
            $table->date('payment_date')->nullable();
            $table->date('paid_date')->nullable();
            $table->decimal('payable_amount','13',4)->nullable();
            $table->decimal('paid_amount','13',4)->nullable();
            $table->string('service_provided')->nullable();
            $table->string('module')->nullable();
            $table->string('work_hour')->nullable();
            $table->text('description')->nullable();
            $table->text('other')->nullable();
            $table->boolean('status')->default(0);
            $table->integer('user_id')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_payments');
    }
}
