<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInInstagramAutomatedMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instagram_automated_messages', function (Blueprint $table) {
            $table->integer('account_id')->nullable();
            $table->integer('target_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instagram_automated_messages', function (Blueprint $table) {
            $table->dropColumn(['account_id', 'target_id']);
        });
    }
}