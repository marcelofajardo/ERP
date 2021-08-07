<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsInAssetsManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assets_manager', function (Blueprint $table) {
            $table->string('provider_name')->nullable()->after('name');
            $table->string('currency')->nullable()->after('amount');
            $table->string('password')->nullable()->after('name');
            $table->string('location')->nullable()->after('purchase_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assets_manager', function (Blueprint $table) {
            $table->dropColumn('provider_name');
            $table->dropColumn('currency');
            $table->dropColumn('password');
            $table->dropColumn('location');

        });
    }
}
