<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldtochatmsgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('chat_messages', 'ticket_id')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->integer('ticket_id')->unsigned()->nullable()->after('sent_to_user_id');
                $table->foreign('ticket_id')->references('id')->on('tickets');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            //
        });
    }
}
