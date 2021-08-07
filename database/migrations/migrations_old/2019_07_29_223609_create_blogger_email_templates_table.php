<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBloggerEmailTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogger_email_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('from')->nullable();
            $table->string('subject')->nullable();
            $table->string('message')->nullable();
            $table->string('cc')->nullable();
            $table->string('bcc')->nullable();
            $table->string('type')->nullable();
            $table->text('other')->nullable();
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
        Schema::dropIfExists('blogger_email_templates');
    }
}
