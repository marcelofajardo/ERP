<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGiftCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sender_name')->nullable();
            $table->string('sender_email')->nullable();
            $table->string('receiver_name')->nullable();
            $table->string('receiver_email')->nullable();
            $table->string('gift_card_coupon_code')->nullable()->index();
            $table->text('gift_card_description')->nullable();
            $table->double('gift_card_amount',8,2)->nullable();
            $table->string('gift_card_message')->nullable();
            $table->dateTime('expiry_date',0)->nullable();
            $table->integer('store_website_id');
            $table->foreign('store_website_id')->references('id')->on('store_websites');
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
        Schema::dropIfExists('gift_cards');
    }
}
