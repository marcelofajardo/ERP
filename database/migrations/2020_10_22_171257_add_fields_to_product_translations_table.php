<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToProductTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_translations', function (Blueprint $table) {
            $table->longText('composition')->nullable();
            $table->string('color')->nullable();
            $table->text('size')->nullable();
            $table->string('country_of_manufacture')->nullable();
            $table->string('dimension')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_translations', function (Blueprint $table) {
            $table->dropColumn('composition');
            $table->dropColumn('color');
            $table->dropColumn('size');
            $table->dropColumn('country_of_manufacture');
            $table->dropColumn('dimension');
        });
    }
}
