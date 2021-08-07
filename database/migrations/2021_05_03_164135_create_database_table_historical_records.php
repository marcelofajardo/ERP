<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatabaseTableHistoricalRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        if(!Schema::hasTable('database_table_historical_records')){
            Schema::create('database_table_historical_records', function (Blueprint $table) {
                $table->increments('id');
                $table->string('database_name');
                $table->double('size', 8, 2);
                $table->unsignedBigInteger('database_id');
                //$table->foreign('database_id')->references('id')->on('database_historical_records');
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
        //
    }
}
