<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableChatMessagesIsEmailField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table("chat_messages",function(Blueprint $table) {
            $table->integer('is_email')->default(0)->nullable()->after('is_reminder');
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
        Schema::table("chat_messages",function(Blueprint $table) {
            $table->dropField('is_email');
        });
    }
}
