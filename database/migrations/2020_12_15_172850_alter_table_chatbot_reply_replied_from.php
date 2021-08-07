<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableChatbotReplyRepliedFrom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('chatbot_replies',function(Blueprint $table) {
            $table->string('reply_from')->nullable()->index()->after('replied_chat_id');
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
            $table->dropField('reply_from');
        });
    }
}
