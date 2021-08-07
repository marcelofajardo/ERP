<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateColumnToGoogleSearchAnlyticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('google_search_analytics', function (Blueprint $table) {
            $table->date('date')->nullable()->after('search_apperiance');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('google_search_analytics', function (Blueprint $table) {
            $table->dropColumn('date');
        });
    }
}
