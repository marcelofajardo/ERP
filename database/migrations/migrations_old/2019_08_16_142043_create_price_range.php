<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceRange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'brand_category_price_range', function ( Blueprint $table ) {
            $table->increments( 'id' );
            $table->integer( 'category_id' );
            $table->string( 'brand_segment', 2 );
            $table->integer( 'min_price' );
            $table->integer( 'max_price' );
            $table->timestamps();
            $table->softDeletes();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'brand_category_price_range' );
    }
}
