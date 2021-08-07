<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFieldsToProductSuppliers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_suppliers', function (Blueprint $table) {
          $table->string('title')->nullable()->after('supplier_id');
          $table->longtext('description')->nullable()->after('title');
          $table->string('supplier_link')->nullable()->after('description');
          $table->string('color')->nullable()->after('size');
          $table->string('composition')->nullable()->after('color');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_suppliers', function (Blueprint $table) {
          $table->dropColumn('title');
          $table->dropColumn('description');
          $table->dropColumn('supplier_link');
          $table->dropColumn('color');
          $table->dropColumn('composition');
        });
    }
}
