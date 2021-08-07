<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexingToChatMessagesQuickDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chat_messages_quick_datas', function (Blueprint $table) {
            $table->index('model_id');
            $table->index('last_unread_message_id');
            $table->index('last_communicated_message_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chat_messages_quick_datas', function (Blueprint $table) {
            $table->dropIndex('model_id');
            $table->dropIndex('last_unread_message_id');
            $table->dropIndex('last_communicated_message_id');
        });
    }
}
