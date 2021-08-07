<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProformaDetailsToPurchase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
          $table->string('proforma_id')->nullable()->after('proforma_confirmed');
          $table->datetime('proforma_date')->nullable()->after('proforma_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
          $table->dropColumn('proforma_id');
          $table->dropColumn('proforma_date');
        });
    }
}
