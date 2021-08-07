<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtAndDeleteByMostUsedPharse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chat_message_phrases', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable()->after("chat_id");
            $table->integer('deleted_by')->nullable()->after("deleted_at");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chat_message_phrases', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
            $table->dropColumn('deleted_by');
        });
    }
}
