<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSuppliersTableAddScraperColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
             $table->string('scraper_start_time')->nullable()->after('scraper_total_urls');
             $table->text('scraper_logic', 65535)->nullable()->after('scraper_start_time');
             $table->integer('scraper_madeby')->nullable()->after('scraper_logic');
             $table->integer('scraper_priority')->nullable()->default(0)->after('scraper_madeby');
             $table->integer('scraper_parent_id')->nullable()->default(0)->after('scraper_priority');
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
            $table->dropColumn('scraper_start_time');
            $table->dropColumn('scraper_logic');
            $table->dropColumn('scraper_madeby');
            $table->dropColumn('scraper_priority');
        });
    }
}
