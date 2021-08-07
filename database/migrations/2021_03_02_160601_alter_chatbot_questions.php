<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterChatbotQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("chatbot_questions",function(Blueprint $table) {
            $table->integer('watson_account_id')->default(0)->nullable()->after('dynamic_reply');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("chatbot_questions",function(Blueprint $table) {
            $table->dropField('watson_account_id');
        });
    }
}
