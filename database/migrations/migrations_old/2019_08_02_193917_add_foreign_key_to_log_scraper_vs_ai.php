<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToLogScraperVsAi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'log_scraper_vs_ai', function ( Blueprint $table ) {
            $table->unsignedInteger( 'product_id' )->change();
        } );

        Schema::table( 'log_scraper_vs_ai', function ( Blueprint $table ) {
            $table->foreign( 'product_id' )->references( 'id' )->on( 'products' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table( 'log_scraper_vs_ai', function ( Blueprint $table ) {
            $table->dropForeign( 'log_scraper_vs_ai_product_id_foreign' );
        } );

        Schema::table( 'log_scraper_vs_ai', function ( Blueprint $table ) {
            $table->bigInteger( 'product_id' )->change();
        } );
    }
}
