<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterChatMessageQuickDatas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chat_messages_quick_datas', function (Blueprint $table) {
            $table->unsignedInteger('last_unread_message_id')->nullable()->after('model_id')->default(null);
            $table->unsignedInteger('last_communicated_message_id')->nullable()->after('last_communicated_message')->default(null);
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
            $table->dropColumn('last_unread_message_id');
            $table->dropColumn('last_communicated_message_id');
        });
    }
}
