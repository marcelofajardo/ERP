<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteDevelopmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_developments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_development_category_id')->nullable();
            $table->integer('status')->default(0);
            $table->string('title');
            $table->string('description');
            $table->integer('developer_id')->nullable();
            $table->integer('website_id');
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
        Schema::dropIfExists('site_developments');
    }
}
