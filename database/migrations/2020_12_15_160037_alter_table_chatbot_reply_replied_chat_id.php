<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableChatbotReplyRepliedChatId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE `chatbot_replies` ADD INDEX(`chat_id`);");
        //
        Schema::table('chatbot_replies',function(Blueprint $table) {
            $table->integer('replied_chat_id')->nullable()->index()->after('chat_id');
            $table->text('answer')->nullable()->after('question');
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
        Schema::table('chatbot_replies',function(Blueprint $table) {
            $table->dropField('replied_chat_id');
            $table->dropField('answer');
        });
    }
}
