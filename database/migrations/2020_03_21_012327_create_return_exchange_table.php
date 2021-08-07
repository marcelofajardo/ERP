<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnExchangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_exchanges', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->index();
            $table->enum('type', ['refund', 'exchange']);
            $table->string('reason_for_refund')->nullable();
            $table->decimal('refund_amount',8,2)->nullable()->default("0.00");
            $table->integer('status');
            $table->text('pickup_address')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('return_exchanges');
    }
}
