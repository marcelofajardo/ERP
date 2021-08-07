<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsToReturnExchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('return_exchanges', function (Blueprint $table) {
            $table->string('refund_amount_mode')->nullable();
            $table->string('chq_number')->nullable();
            $table->string('awb')->nullable();
            $table->string('payment')->nullable();
            $table->timestamp('date_of_refund')->nullable();
            $table->timestamp('date_of_issue')->nullable();
            $table->text('details')->nullable();
            $table->timestamp('dispatch_date')->nullable();
            $table->timestamp('date_of_request')->nullable();
            $table->boolean('credited')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('return_exchanges', function (Blueprint $table) {
            //
        });
    }
}
