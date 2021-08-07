<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToScrapProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scraped_products', function (Blueprint $table) {
            $table->integer('is_external_scraper')->default(0);
            $table->longText('size')->nullable();
            $table->longText('material_used')->nullable();
            $table->longText('country')->nullable();
            $table->longText('supplier')->nullable();
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scraped_products', function (Blueprint $table) {
            $table->dropField('is_external_scraper');
            $table->dropField('size');
            $table->dropField('material_used');
            $table->dropField('country');
            $table->dropField('supplier');
        });
    }
}
