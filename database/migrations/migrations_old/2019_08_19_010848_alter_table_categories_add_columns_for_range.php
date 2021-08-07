<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCategoriesAddColumnsForRange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'categories', function ( $table ) {
            $table->string( 'dimension_range' )->after( 'show_all_id' );
            $table->string( 'size_range' )->after( 'dimension_range' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table( 'categories', function ( $table ) {
            $table->dropColumn( 'dimension_range' );
            $table->dropColumn( 'size_range' );
        } );
    }
}
