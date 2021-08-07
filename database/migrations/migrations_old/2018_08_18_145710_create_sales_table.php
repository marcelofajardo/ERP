<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('author_id')->nullable()->unsigned();
	        $table->date('date_of_request')->nullable();
            $table->string('sales_person_name')->nullable();
            $table->string('client_name')->nullable();
            $table->string('client_phone')->nullable();
            $table->string('description',500)->nullable();
            $table->string('allocated_to')->nullable();
            $table->time('finished_at')->nullable();
	        $table->boolean('check_1')->default(0);
	        $table->boolean('check_2')->default(0);
	        $table->boolean('check_3')->default(0);
	        $table->time('sent_to_client')->nullable();
	        $table->string('remark')->nullable();
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
        Schema::dropIfExists('sales');
    }
}
