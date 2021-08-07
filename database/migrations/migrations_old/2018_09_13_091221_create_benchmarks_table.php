<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBenchmarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('benchmarks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('selections')->default(0);
            $table->integer('searches')->default(0);
            $table->integer('attributes')->default(0);
            $table->integer('supervisor')->default(0);
            $table->integer('imagecropper')->default(0);
            $table->integer('lister')->default(0);
            $table->integer('approver')->default(0);
            $table->integer('inventory')->default(0);
            $table->date('for_date');
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
        Schema::dropIfExists('benchmarks');
    }
}
