<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLogScraperAddColumnRaw extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'log_scraper', function ( $table ) {
            $table->text( 'raw_data' )->nullable()->after( 'validation_result' );
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
            $table->dropColumn( 'raw_data' );
        } );
    }
}
