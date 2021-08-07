<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFromDestionationColumnsInHsCodeSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('hs_code_settings', function (Blueprint $table) {
//            $table->string('from_country')->nullable()->after('key');
//            $table->string('destination_country')->nullable()->after('key');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hs_code_settings', function (Blueprint $table) {
            $table->dropColumn('from_country');
            $table->dropColumn('destination_country');
        });
    }
}
