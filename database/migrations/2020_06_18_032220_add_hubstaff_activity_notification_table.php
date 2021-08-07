<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHubstaffActivityNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hubstaff_activity_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->float('min_percentage')->default("0.00");
            $table->float('actual_percentage')->default("0.00");
            $table->text('reason')->nullable();
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
        Schema::dropIfExists('hubstaff_activity_notifications');
    }
}
