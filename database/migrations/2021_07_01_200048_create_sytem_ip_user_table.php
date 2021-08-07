<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSytemIpUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_system_ip', function (Blueprint $table) {
            $table->increments('id');
            $table->string('index_txt')->nullable();
            $table->ipAddress('ip');
            $table->integer('user_id')->nullable();
            $table->integer('other_user_name')->nullable();
            $table->boolean('is_active')->default(1);
            $table->longText('notes')->nullable();
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
        Schema::dropIfExists('user_system_ip');
    }
}
