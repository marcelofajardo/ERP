<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBrandsAddSalesColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('brands', function(Blueprint $table)
        {
            $table->integer('sales_discount')->after('deduction_percentage')->default(0);
            $table->integer('b2b_sales_discount')->after('deduction_percentage')->default(0);
            $table->integer('apply_b2b_discount_above')->after('deduction_percentage')->default(0);
            $table->integer('flash_sales_percentage')->after('deduction_percentage')->default(0);
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
            $table->dropColumn('sales_discount');
            $table->dropColumn('apply_b2b_discount_above');
            $table->dropColumn('b2b_sales_discount');
            $table->dropColumn('flash_sales_percentage');
        });
    }
}
