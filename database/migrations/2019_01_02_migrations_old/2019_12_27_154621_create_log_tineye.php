<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogTineye extends Migration
{
    public function up()
    {
        Schema::create( 'log_tineye', function ( Blueprint $table ) {
            $table->bigIncrements( 'id' );
            $table->string( 'image_url' );
            $table->string( 'md5', 32 );
            $table->text( 'response' );
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
        Schema::dropIfExists( 'log_tineye' );
    }
}
