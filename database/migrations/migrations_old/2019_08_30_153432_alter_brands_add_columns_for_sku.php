<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBrandsAddColumnsForSku extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->string('sku_strip_last')->after('brand_segment')->nullable();
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->string('sku_add')->after('sku_strip_last')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('sku_strip_last');
            $table->dropColumn('sku_add');
        });
    }
}
