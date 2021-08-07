<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatbotQuestionErrorLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_bot_error_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('chatbot_question_id');
            $table->string('type')->nullable();
            $table->string('request')->nullable();
            $table->string('response')->nullable();
            $table->string('response_type')->nullable();
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
        Schema::dropIfExists('chat_bot_error_logs');
    }
}
