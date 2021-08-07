<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCustomerBasket extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('customer_baskets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("customer_id")->nullable();
            $table->string("customer_name")->nullable();
            $table->string("customer_email")->nullable();
            $table->integer("store_website_id")->nullable();
            $table->string("language_code")->nullable();
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
        //
        Schema::dropIfExists('customer_baskets');
    }
}
