<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableLogRequestAddField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table("log_requests",function(Blueprint $table) {
            $table->string("time_taken")->after('status_code');
            $table->datetime("start_time")->after('time_taken')->nullable();
            $table->datetime("end_time")->after('start_time')->nullable();
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
        Schema::table("log_requests",function(Blueprint $table) {
            $table->dropField("time_taken");
            $table->dropField("start_time");
            $table->dropField("end_time");
        });
    }
}
