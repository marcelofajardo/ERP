<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScrapInfluencersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scrap_influencers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('followers')->nullable();
            $table->string('following')->nullable();
            $table->string('posts')->nullable();
            $table->string('description')->nullable();
            $table->string('url')->nullable();
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
        Schema::dropIfExists('scrap_influencers');
    }
}
