<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstaMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insta_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('number')->nullable();
            $table->text('message');
            $table->integer('lead_id');
            $table->integer('order_id')->nullable();
            $table->integer('approved')->default(0);
            $table->integer('status')->default(0);
            $table->string('media_url')->nullable();
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
        Schema::dropIfExists('insta_messages');
    }
}
