<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('user_rates')){
            Schema::create('user_rates', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->dateTime('start_date');
                $table->float('hourly_rate');
                $table->string('currency');
                $table->timestamps();
            });
        }
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_rates', function (Blueprint $table) {
            $table->dropIfExists();
        });
    }
}
