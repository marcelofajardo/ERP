<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageQueueHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('message_queue_history', function (Blueprint $table) {

		$table->integer('id',11);
		$table->string('number',180);
		$table->integer('counter')->default('0');
		$table->timestamp('time')->default(\DB::raw('CURRENT_TIMESTAMP'));

        });
    }

    public function down()
    {
        Schema::dropIfExists('message_queue_history');
    }
}