<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColdLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cold_leads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('username');
            $table->string('platform');
            $table->string('platform_id')->nullable();
            $table->integer('rating');
            $table->text('image')->nullable();
            $table->text('bio')->nullable();
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
        Schema::dropIfExists('cold_leads');
    }
}
