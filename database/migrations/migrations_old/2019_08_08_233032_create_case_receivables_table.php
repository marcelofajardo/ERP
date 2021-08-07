<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaseReceivablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_receivables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('case_id')->unsigned();
            $table->foreign('case_id')->references('id')->on('cases');
            $table->integer('currency')->default(0);
            $table->date('receivable_date')->nullable();
            $table->date('received_date')->nullable();
            $table->decimal('receivable_amount',13,4)->nullable();
            $table->decimal('received_amount',13,4)->nullable();
            $table->text('description')->nullable();
            $table->text('other')->nullable();
            $table->boolean('status')->default(0);
            $table->integer('user_id')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('case_receivables');
    }
}
