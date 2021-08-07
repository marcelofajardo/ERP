<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableWebsiteStores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('website_stores',function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->string('code')->nullable();
            $table->string('root_category')->nullable();
            $table->string('platform_id')->nullable();
            $table->integer('website_id')->nullable();
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
        //
        Schema::dropIfExists('website_stores');
    }
}
