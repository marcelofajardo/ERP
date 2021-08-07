<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeRankingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_e_ranking', function (Blueprint $table) {
            $table->integer('id')->nullable();
            $table->string('name')->nullable();
            $table->string('group_id')->nullable();
            $table->string('link')->nullable();
            $table->date('first_check_date')->nullable();
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
        Schema::dropIfExists('s_e_ranking');
    }
}
