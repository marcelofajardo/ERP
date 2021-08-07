<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLogExcelImportsAddColumnNumberProductsUpdated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_excel_imports', function ($table) {
            $table->integer('number_products_updated')->nullable()->after('number_of_products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('log_excel_imports', function ($table) {
            $table->dropColumn('number_products_updated');
        });
    }
}
