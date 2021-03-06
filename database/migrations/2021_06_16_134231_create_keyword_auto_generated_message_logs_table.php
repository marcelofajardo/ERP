<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeywordAutoGeneratedMessageLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keyword_auto_genrated_message_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('model')->nullable()->index();
            $table->string('model_id')->nullable()->index();
            $table->string('keyword')->nullable();
            $table->string('keyword_match')->nullable();
            $table->string('message_sent_id')->nullable()->index();
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('keyword_auto_generated_message_logs');
    }
}
