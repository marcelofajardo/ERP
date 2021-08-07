<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTwilioActiveNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('twilio_active_numbers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sid');
            $table->string('account_sid');
            $table->string('friendly_name');
            $table->string('phone_number');
            $table->string('voice_url');
            $table->string('date_created');
            $table->string('date_updated');
            $table->string('sms_url');
            $table->string('voice_receive_mode')->nullable();
            $table->string('api_version');
            $table->string('voice_application_sid')->nullable();
            $table->string('sms_application_sid')->nullable();
            $table->string('trunk_sid')->nullable();
            $table->string('emergency_status')->nullable();
            $table->string('emergency_address_sid')->nullable();
            $table->string('address_sid')->nullable();
            $table->string('identity_sid')->nullable();
            $table->string('bundle_sid')->nullable();
            $table->string('uri')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('twilio_active_numbers');
    }
}
