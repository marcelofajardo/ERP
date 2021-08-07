<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalColumnToMessageQueues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('message_queues', function (Blueprint $table) {
          $table->string('phone')->nullable()->change();
          $table->string('whatsapp_number')->nullable()->after('phone');
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
          $table->dropColumn('whatsapp_number');
        });
    }
}
