<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLiveChatTableAddSeenAddStatusColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_live_chats', function($table){
            $table->integer('status')->nullable();
            $table->integer('seen')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_live_chats', function($table){
            $table->dropColumn('status');
            $table->dropColumn('seen');
        });
    }
}
