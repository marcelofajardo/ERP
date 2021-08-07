<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreColumnsToSuppliers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
          $table->string('address')->nullable()->after('supplier');
          $table->string('phone')->nullable()->after('address');
          $table->string('email')->nullable()->after('phone');
          $table->string('social_handle')->nullable()->after('email');
          $table->string('gst')->nullable()->after('social_handle');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
          $table->dropColumn('address');
          $table->dropColumn('phone');
          $table->dropColumn('email');
          $table->dropColumn('social_handle');
          $table->dropColumn('gst');
        });
    }
}
