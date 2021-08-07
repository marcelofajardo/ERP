<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterChatMessagesAddColumnsIsDeliveredIsRead extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'chat_messages', function ( $table ) {
            $table->tinyInteger('is_read')->unsigned()->default(0)->after('sent');
            $table->tinyInteger('is_delivered')->unsigned()->default(0)->after('sent');
            $table->string('unique_id')->nullable()->after('id');
        } );
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {
        Schema::table( 'chat_messages', function ( $table ) {
            $table->dropColumn( 'unique_id' );
            $table->dropColumn( 'is_delivered' );
            $table->dropColumn( 'is_read' );
        } );
    }
}
