<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblLeadHubstafDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_hubstaff_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('hubstaff_task_id');
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('team_lead_id');
            $table->boolean('current')->default(0);
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
    }
}
