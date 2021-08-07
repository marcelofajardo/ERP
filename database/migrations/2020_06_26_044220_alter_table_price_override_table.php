<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePriceOverrideTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_overrides',function($table){
            $table->string('brand_segment')->nullable()->after("brand_id");
            $table->integer('store_website_id')->nullable()->after("id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_overrides',function($table){
            $table->dropColumn('brand_segment');
            $table->dropColumn('store_website_id');
        });
    }
}
