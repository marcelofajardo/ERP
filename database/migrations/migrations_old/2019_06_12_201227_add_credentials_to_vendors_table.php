<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCredentialsToVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
          $table->string('website')->nullable()->after('social_handle');
          $table->string('login')->nullable()->after('website');
          $table->string('password')->nullable()->after('login');
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
          $table->dropColumn('website');
          $table->dropColumn('login');
          $table->dropColumn('password');
        });
    }
}
