<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToChatbotQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chatbot_questions', function (Blueprint $table) {
            $table->string('keyword_or_question')->nullable();
            $table->datetime('sending_time')->nullable();
            $table->string('repeat')->nullable();
            $table->integer('is_active')->default(0);
            $table->string('erp_or_watson')->nullable();
            $table->boolean('auto_approve')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chatbot_questions', function (Blueprint $table) {
            //
        });
    }
}
