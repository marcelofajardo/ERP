<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitorLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip');
            $table->string('browser');
            $table->string('location');
            $table->text('page');
            $table->string('customer_name')->nullable();
            $table->string('customer_number')->nullable();
            $table->integer('visits')->default(1);
            $table->dateTime('last_visit');
            $table->string('page_current');
            $table->integer('chats');
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
        Schema::dropIfExists('visitor_logs');
    }
}
