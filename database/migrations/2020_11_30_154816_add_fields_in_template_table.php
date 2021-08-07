<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsInTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_templates', function (Blueprint $table) {
            $table->string('text')->nullable()->after('product_id');
            $table->string('font_style')->nullable()->after('text');
            $table->string('font_size')->nullable()->after('font_style');
            $table->string('background_color')->nullable()->after('font_size');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_templates', function (Blueprint $table) {
            $table->dropColumn('text');
            $table->dropColumn('font_style');
            $table->dropColumn('font_size');
            $table->dropColumn('background_color');
        });
    }
}
