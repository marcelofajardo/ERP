<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailinglistTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailinglist_templates', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->increments('id');
            $table->string("name");
            $table->unsignedInteger("image_count");
            $table->unsignedInteger("text_count");
            $table->text("example_image");
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
        Schema::dropIfExists('mailinglist_templates');
    }
}
