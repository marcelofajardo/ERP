<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableChatMessagePaymentReceiptId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->integer('payment_receipt_id')->nullable()->after('site_development_id');
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
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropField('payment_receipt_id');
        });
    }
}
