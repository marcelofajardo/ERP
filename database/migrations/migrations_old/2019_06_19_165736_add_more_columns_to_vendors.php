<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreColumnsToVendors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
          $table->string('account_name')->nullable()->after('gst');
          $table->string('account_iban')->nullable()->after('account_name');
          $table->string('account_swift')->nullable()->after('account_iban');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
          $table->dropColumn('account_name');
          $table->dropColumn('account_iban');
          $table->dropColumn('account_swift');
        });
    }
}
