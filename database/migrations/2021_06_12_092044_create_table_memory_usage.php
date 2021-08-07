<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMemoryUsage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memory_usage', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('total')->nullable();
            $table->integer('used')->nullable();
            $table->integer('free')->nullable();
            $table->integer('buff_cache')->nullable();
            $table->integer('available')->nullable();
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
        //
    }
}
