<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id');
           // $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('ticket_id',255)->nullable();
            $table->string('subject')->nullable();
            $table->text('message')->nullable();
            $table->string('assigned_to')->nullable();
            $table->integer('status_id')->default(0);
            $table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP')); 
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
        Schema::dropIfExists('tickets');
    }
}
