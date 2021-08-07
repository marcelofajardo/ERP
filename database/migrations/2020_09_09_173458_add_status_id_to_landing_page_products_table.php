<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusIdToLandingPageProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('landing_page_products', function (Blueprint $table) {
            if (!Schema::hasColumn('landing_page_products', 'landing_page_status_id')) {
                $table->integer('landing_page_status_id')->unsigned()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('landing_page_products', function (Blueprint $table) {
            $table->dropColumn('landing_page_status_id');
        });
    }
}
