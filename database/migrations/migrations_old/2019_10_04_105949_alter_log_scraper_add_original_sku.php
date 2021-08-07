<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLogScraperAddOriginalSku extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'log_scraper', function ( $table ) {
            $table->text( 'original_sku' )->nullable()->after( 'sku' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table( 'log_scraper', function ( $table ) {
            $table->dropColumn( 'original_sku' );
        } );
    }
}