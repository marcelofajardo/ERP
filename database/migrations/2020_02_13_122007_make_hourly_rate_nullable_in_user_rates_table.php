<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeHourlyRateNullableInUserRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_rates', function (Blueprint $table) {
            $table->float('hourly_rate')->nullable()->change();
            $table->string('currency')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_rates', function (Blueprint $table) {
            $table->float('hourly_rate');
            $table->string('currency');
        });
    }
}
