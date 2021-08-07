<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDropForeignKeyPageNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        // Schema::table('page_notes', function (Blueprint $table) {
        //     $table->dropForeign('page_notes_category_id_foreign');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        /*Schema::table('page_notes', function (Blueprint $table) {
            // $table->unsignedInteger('category_id');
  
            $table->foreign('category_id')
                  ->references('id')
                  ->on('page_notes_categories')
                  ->onDelete('cascade');
          });*/
    }
}
