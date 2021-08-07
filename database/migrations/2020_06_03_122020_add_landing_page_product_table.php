<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLandingPageProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('landing_page_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->integer('status');
            $table->timestamp('start_date')->default("0000-00-00 00:00");
            $table->timestamp('end_date')->default("0000-00-00 00:00");
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
        Schema::dropIfExists('landing_page_products');
    }
}
