<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBackLinkCheckerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('back_link_checker', function (Blueprint $table) {
            $table->increments('id');
            $table->string('domains');
            $table->string('links');
            $table->string('link_type');
            $table->string('review_numbers');
            $table->integer('rank');
            $table->string('rating');
            $table->integer('serp_id');
            $table->text('snippet');
            $table->text('title');
            $table->string('visible_link');
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
        Schema::dropIfExists('back_link_checker');
    }
}
