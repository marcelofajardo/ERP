<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableChatbotRepliesField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table("chatbot_replies",function(Blueprint $table) {
            $table->integer("is_read")->comment("0 => Unread , 1 => Read")->default(0)->after("reply_from");
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
        Schema::table("chatbot_replies",function(Blueprint $table) {
            $table->dropField("is_read");
        });
    }
}
