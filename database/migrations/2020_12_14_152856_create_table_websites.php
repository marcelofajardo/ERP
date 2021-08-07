<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableWebsites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('websites',function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->string('code')->nullable();
            $table->string('sort_order')->nullable();
            $table->string('platform_id')->nullable();
            $table->integer('store_website_id')->nullable();
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
        Schema::dropIfExists('websites');
    }
}
