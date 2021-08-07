<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToSuppliers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
          $table->string('instagram_handle')->nullable()->after('social_handle');
          $table->string('website')->nullable()->after('instagram_handle');
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
        $table->dropColumn('instagram_handle');
        $table->dropColumn('website');
      });
    }
}
