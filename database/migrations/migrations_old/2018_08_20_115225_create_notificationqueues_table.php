<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationqueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notificationqueues', function (Blueprint $table) {
            $table->increments('id');
	        $table->string('role');
	        $table->string('message');
	        $table->string('user_id');
	        $table->string('sale_id');
	        $table->string('sent_to');
	        $table->timestamp('time_to_add');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notificationqueues');
    }
}
