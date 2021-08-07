<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSupplierEmailToLogExcelImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_excel_imports', function (Blueprint $table) {
            $table->string('supplier_email')->nullable()->after('number_of_products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_excel_imports', function (Blueprint $table) {
            $table->dropColumn('supplier_email');
        });
    }
}
