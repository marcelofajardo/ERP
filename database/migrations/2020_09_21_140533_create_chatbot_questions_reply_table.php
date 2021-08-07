<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatbotQuestionsReplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('chatbot_questions_reply')){
            Schema::create('chatbot_questions_reply', function (Blueprint $table) {
                $table->increments('id');
                $table->string('suggested_reply');
                $table->integer('store_website_id')->unsigned();
                $table->integer('chatbot_question_id')->unsigned();
                $table->timestamps();
                //$table->foreign('store_website_id')->references('id')->on('store_websites')->onDelete('cascade');
                //$table->foreign('chatbot_question_id')->references('id')->on('chatbot_questions')->onDelete('cascade');
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
        Schema::dropIfExists('chatbot_questions_reply');
    }
}
