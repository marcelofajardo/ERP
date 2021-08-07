<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSpeedColumnInCroppedImageReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cropped_image_references', function (Blueprint $table) {
            $table->string('speed')->nullable()->after('new_media_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cropped_image_references', function (Blueprint $table) {
            $table->dropColumn('speed');
        });
    }
}
