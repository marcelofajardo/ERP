<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToChatbotQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chatbot_questions', function (Blueprint $table) {
            $table->integer('task_category_id')->nullable();
            $table->integer('assigned_to')->nullable();
            $table->text('task_description')->nullable();
            $table->string('task_type')->nullable();
            $table->integer('repository_id')->nullable();
            $table->integer('module_id')->nullable();
            $table->boolean('dynamic_reply')->default(0);
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
