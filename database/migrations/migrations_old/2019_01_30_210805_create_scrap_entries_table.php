<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScrapEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scrap_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('url');
            $table->boolean('is_scraped')->default(0);
            $table->boolean('is_product_page')->default(0);
            $table->text('pagination')->nullable();
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
        Schema::dropIfExists('scrap_entries');
    }
}
