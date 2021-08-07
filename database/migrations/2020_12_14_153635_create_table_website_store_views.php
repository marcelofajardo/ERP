<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableWebsiteStoreViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('website_store_views',function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->string('code')->nullable();
            $table->integer('status')->default(0);
            $table->string('sort_order')->default(0);
            $table->string('platform_id')->nullable();
            $table->integer('website_store_id')->nullable();
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
        Schema::dropIfExists('website_store_views');
    }
}
