<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterScrapedProductsAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'scraped_products', function ( $table ) {
            $table->integer( 'price_eur' )->after( 'price' );
            $table->integer( 'discounted_price_eur' )->after( 'price_eur' );
            $table->index( 'sku' );
        } );

        Schema::table( 'products', function ( $table ) {
            $table->index( 'sku' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table( 'scraped_products', function ( $table ) {
            $table->dropColumn( 'price_eur' );
            $table->dropColumn( 'discounted_price_eur' );
            $table->dropIndex( 'scraped_products_sku_index' );
        } );

        Schema::table( 'products', function ( $table ) {
            $table->dropIndex( 'products_sku_index' );
        } );
    }
}