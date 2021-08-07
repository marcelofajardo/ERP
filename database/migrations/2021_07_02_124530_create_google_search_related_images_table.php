<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoogleSearchRelatedImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_search_related_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('google_search_image_id')->nullable();
            $table->longText('google_image')->nullable();
            $table->longText('image_url')->nullable();
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
        Schema::dropIfExists('google_search_related_images');
    }
}
