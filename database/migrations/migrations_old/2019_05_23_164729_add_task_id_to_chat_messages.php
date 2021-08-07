<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaskIdToChatMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chat_messages', function (Blueprint $table) {
          $table->integer('task_id')->unsigned()->nullable()->after('user_id');
          $table->integer('erp_user')->unsigned()->nullable()->after('task_id');

          $table->foreign('task_id')->references('id')->on('tasks');
          $table->foreign('erp_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chat_messages', function (Blueprint $table) {
          $table->dropForeign(['task_id']);
          $table->dropForeign(['erp_user']);

          $table->dropColumn('task_id');
          $table->dropColumn('erp_user');
        });
    }
}
