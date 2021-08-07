<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPushErrorLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_push_error_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->integer('store_website_id')->nullable();
            $table->text('message')->nullable();
            $table->text('request_data')->nullable();
            $table->text('response_data')->nullable();
            $table->string('response_status')->nullable();
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
        Schema::dropIfExists('product_push_error_logs');
    }
}
