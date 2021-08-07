<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewInstagramConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instagram_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number');
            $table->string('provider')->nullable();
            $table->string('username');
            $table->text('password');
            $table->integer('is_customer_support')->default(0);
            $table->integer('frequency')->nullable();
            $table->dateTime('last_online')->nullable();
            $table->integer('is_connected')->default(0);
            $table->integer('send_start');
            $table->integer('send_end');
            $table->string('instance_id')->nullable();
            $table->string('token')->nullable();
            $table->string('status')->default(0);
            $table->string('is_default')->default(0);
            $table->string('device_name')->nullable();
            $table->string('simcard_number')->nullable();
            $table->string('simcard_owner')->nullable();
            $table->string('sim_card_type')->nullable();
            $table->string('payment')->nullable();
            $table->string('recharge_date')->nullable();

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
        Schema::dropIfExists('new_instagram_configs');
    }
}
