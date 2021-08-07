<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCroppedImageReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cropped_image_references', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('original_media_id');
            $table->string('original_media_name');
            $table->integer('new_media_id');
            $table->string('new_media_name');
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
        Schema::dropIfExists('cropped_image_references');
    }
}
