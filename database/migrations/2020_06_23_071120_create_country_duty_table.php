<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountryDutyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_duties', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hs_code');
            $table->string('origin');
            $table->string('destination');
            $table->string('currency');
            $table->decimal('price');
            $table->decimal('duty');
            $table->decimal('vat');
            $table->decimal('duty_percentage');
            $table->decimal('vat_percentage');
            $table->integer('duty_group_id')->nullable();
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
        Schema::dropIfExists('country_duties');
    }
}
