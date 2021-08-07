<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->integer('model_id')->nullable();
            $table->string('model_type')->nullable();
            $table->decimal('used_credit',15,2)->nullable();
            $table->string('used_in')->comment('e.g. for order so value will be like ORDER');
            $table->string('type')->nullable()->comment('value added or minus so value will be ADD, MINUS');
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
        Schema::dropIfExists('credit_history');
    }
}
