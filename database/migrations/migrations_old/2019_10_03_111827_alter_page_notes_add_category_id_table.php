<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPageNotesAddCategoryIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'page_notes', function ( $table ) {
            $table->integer('category_id')->unsigned()->nullable()->after('url');
            $table->foreign('category_id')->references('id')->on('page_notes_categories')->onDelete('cascade');
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table( 'page_notes', function ( $table ) {
            $table->dropColumn( 'category_id' );
        } );
    }
}
