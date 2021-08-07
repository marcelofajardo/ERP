<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SystemSizeManger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_size_managers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id');
            $table->string('erp_size');
            $table->integer('status')->default(1)->comment('0-Deleted, 1-Not deleted');
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
        Schema::dropIfExists('system_size_managers');
    }
}
