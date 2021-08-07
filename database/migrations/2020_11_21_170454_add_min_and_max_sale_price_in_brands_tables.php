<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMinAndMaxSalePriceInBrandsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->integer('max_sale_price')->nullable()->after('euro_to_inr');
            $table->integer('min_sale_price')->nullable()->after('euro_to_inr');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropColumn('max_sale_price');
        $table->dropColumn('min_sale_price');
    }
}
