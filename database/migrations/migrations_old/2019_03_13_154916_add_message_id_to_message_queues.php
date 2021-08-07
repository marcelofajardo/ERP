<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMessageIdToMessageQueues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('message_queues', function (Blueprint $table) {
          $table->integer('chat_message_id')->unsigned()->after('customer_id')->nullable();
          $table->foreign('chat_message_id')->references('id')->on('chat_messages');

          $table->integer('group_id')->unsigned()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('message_queues', function (Blueprint $table) {
          $table->dropForeign(['chat_message_id']);
          $table->dropColumn('group_id');
        });
    }
}
