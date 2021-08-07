<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFormatFromIntegerToStringSimplyDutyCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('simply_duty_countries', function (Blueprint $table) {
            $table->string('country_code')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('simply_duty_countries', function (Blueprint $table) {
           $table->integer('country_code')->change(); 
        });
    }
}
