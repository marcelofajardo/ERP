<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AtlerAddColumnMessageQueueHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('message_queue_history', function (Blueprint $table) {
            $table->enum('type',['individual','group'])->after('counter');
            $table->string("user_id")->after('type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('message_queue_history', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('user_id');
        });
    }
}
