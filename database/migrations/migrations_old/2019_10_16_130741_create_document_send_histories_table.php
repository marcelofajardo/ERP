<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentSendHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_send_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('send_by');
            $table->integer('send_to');
            $table->string('type')->nullable();
            $table->string('via')->nullable();
            $table->string('remarks')->nullable();
            $table->integer('document_id')->nullable();

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
        Schema::dropIfExists('document_send_histories');
    }
}
