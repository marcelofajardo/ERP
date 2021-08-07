<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSupplierInWetransfersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wetransfers', function (Blueprint $table) {
            $table->string('supplier')->nullable()->after("url");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wetransfers', function (Blueprint $table) {
            $table->dropColumn('supplier');
        });
    }
}
