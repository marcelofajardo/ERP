<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatbotKeywordValueTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chatbot_keyword_value_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->integer('chatbot_keyword_value_id');
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
        Schema::drop('chatbot_keyword_value_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->integer('chatbot_keyword_value_id');
            $table->timestamps();
        });
    }
}
