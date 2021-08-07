<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGreetingMessagesToStoreWebsiteTwilioNumbers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_website_twilio_numbers', function (Blueprint $table) {
            $table->string('message_available')->nullable();
            $table->string('message_not_available')->nullable();
            $table->string('message_busy')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_website_twilio_numbers', function (Blueprint $table) {
            $table->dropColumn('message_available');
            $table->dropColumn('message_not_available');
            $table->dropColumn('message_busy');
        });
    }
}
