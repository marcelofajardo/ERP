<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColdLeadBroadcastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cold_lead_broadcasts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('number_of_users');
            $table->integer('frequency');
            $table->dateTime('started_at');
            $table->text('message');
            $table->text('image')->nullable();
            $table->integer('messages_sent')->default(0);
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('cold_lead_broadcasts');
    }
}
