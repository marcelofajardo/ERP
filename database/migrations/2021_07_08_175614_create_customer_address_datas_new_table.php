<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerAddressDatasNewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_address_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('customer_id')->nullable();
            $table->string('entity_id')->nullable();
            $table->string('parent_id')->nullable();
            $table->string('address_type')->nullable();
            $table->string('region')->nullable();
            $table->string('region_id')->nullable();
            $table->string('postcode')->nullable();
            $table->string('firstname')->nullable();
            $table->string('middlename')->nullable();
            $table->string('company')->nullable();
            $table->string('country_id')->nullable();
            $table->string('telephone')->nullable();
            $table->string('prefix')->nullable();
            $table->string('street')->nullable();
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
        Schema::dropIfExists('customer_address_datas');
    }
}
