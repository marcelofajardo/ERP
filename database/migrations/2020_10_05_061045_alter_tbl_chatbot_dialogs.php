<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblChatbotDialogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chatbot_dialogs', function (Blueprint $table) {
            $table->unsignedBigInteger('store_website_id')->nullable();
        });
        Schema::table('chatbot_dialog_responses', function (Blueprint $table) {
            $table->string('condition_sign')->nullable();
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
