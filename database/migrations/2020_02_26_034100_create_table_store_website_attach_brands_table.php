<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableStoreWebsiteAttachBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_website_brands', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_id');
            $table->double('markup', 8, 2)->nullable()->default("0.00");
            $table->integer('store_website_id');
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
        Schema::dropIfExists('store_website_brands');
    }
}
