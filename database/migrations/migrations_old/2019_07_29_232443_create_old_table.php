<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('old', function (Blueprint $table) {
            $table->increments('serial_no');
            $table->string('name');
            $table->text('description');
            $table->integer('amount');
            $table->text('commitment');
            $table->text('communication');
            $table->enum(
                'status',
                ['pending', 'disputed', 'settled', 'paid', 'closed']);
            $table->string('email')->nullable();
            $table->string('number')->nullable();
            $table->string('address')->nullable();
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
        Schema::dropIfExists('old');
    }
}
