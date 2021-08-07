<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogScraperVsAi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'log_scraper_vs_ai', function ( Blueprint $table ) {
            $table->bigIncrements( 'id' );
            $table->bigInteger( 'product_id' );
            $table->string( 'ai_name' );
            $table->text( 'media_input' );
            $table->text( 'result_scraper' );
            $table->text( 'result_ai' );
            $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'log_scraper_vs_ai' );
    }
}
