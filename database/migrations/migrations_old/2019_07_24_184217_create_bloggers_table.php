<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBloggersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bloggers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('default_phone')->nullable();
            $table->string('instagram_handle')->nullable();
            $table->string('agency')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->integer('followers')->nullable();
            $table->integer('followings')->nullable();
            $table->integer('avg_engagement')->nullable();
            $table->integer('fake_followers')->nullable();
            $table->string('email')->nullable();
            $table->string('industry')->nullable();
            $table->string('brands')->nullable();
            $table->string('whatsapp_number')->nullable();
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
        Schema::dropIfExists('bloggers');
    }
}
