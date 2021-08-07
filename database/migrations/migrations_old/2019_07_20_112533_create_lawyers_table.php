<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawyersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lawyers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('default_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('referenced_by')->nullable();
            $table->integer('speciality_id')->nullable()->unsigned();
            $table->foreign('speciality_id')->references('id')->on('lawyer_specialities')->onUpdate('restrict')->onDelete('restrict');
            $table->tinyInteger('rating')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->text('remarks')->nullable();
            $table->text('other')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('lawyers');
    }
}
