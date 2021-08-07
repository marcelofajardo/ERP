<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteDevelopmentArtowrkHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_development_artowrk_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->integer('site_development_id');
            $table->string('from_status');
            $table->string('to_status');
            $table->string('username');
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
        Schema::dropIfExists('site_development_artowrk_histories');
    }
}
