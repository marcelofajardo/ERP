<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePushFcmNotificationHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_fcm_notification_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token')->nullable();
            $table->integer('notification_id')->index() ->nullable();
            $table->integer('success')->default(0)->nullable();
            $table->text('error_message')->nullable();
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
        Schema::dropIfExists('push_fcm_notification_histories');
    }
}
