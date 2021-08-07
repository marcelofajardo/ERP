<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentCallStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_call_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('agent_id')->nullable();
            $table->string('agent_name')->nullable();
            $table->string('agent_name_id')->nullable();
            $table->integer('site_id')->nullable();
            $table->string('twilio_no')->nullable();
            $table->string('status')->default(0)->nullable();
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
        Schema::dropIfExists('agent_call_status');
    }
}
