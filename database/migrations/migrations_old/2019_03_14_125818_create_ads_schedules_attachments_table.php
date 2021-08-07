<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdsSchedulesAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads_schedules_attachments', function (Blueprint $table) {
            $table->integer('ads_schedule_id')->unsigned();
            $table->integer('attachment_id')->unsigned();
            $table->string('attachment_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads_schedules_attachments');
    }
}
