<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSkuFormatAddSkuFormatWithoutColor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sku_formats', function (Blueprint $table) {
            $table->integer('sku_format_without_color')->default(null)->after('sku_format');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sku_formats', function (Blueprint $table) {
            $table->dropColumn('sku_format_without_color');
        });
    }
}
