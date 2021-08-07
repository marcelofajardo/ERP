<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryWhatsappNumberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_whatsapp_number', function (Blueprint $table) {
            $table->increments('id');
			$table->dateTime('date_time');
            $table->string('object');
			$table->integer('object_id');
            $table->string('old_number')->nullable();
            $table->string('new_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_whatsapp_number');
    }
}
