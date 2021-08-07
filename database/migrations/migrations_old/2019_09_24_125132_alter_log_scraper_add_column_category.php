<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLogScraperAddColumnCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'log_scraper', function ( $table ) {
            $table->string( 'category' )->nullable()->after( 'brand' );
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
            $table->dropColumn( 'category' );
        } );
    }
}
