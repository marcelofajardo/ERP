<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateScraperTableAddScraperTotalUrlsScraperExistingUrlsScraperNewUrlsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers',function($table){
            $table->integer('scraper_total_urls')->after('scraper_name');
            $table->integer('scraper_existing_urls')->after('scraper_name');
            $table->integer('scraper_new_urls')->after('scraper_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suppliers',function($table){
            $table->dropColumn('scraper_total_urls');
            $table->dropColumn('scraper_existing_urls');
            $table->dropColumn('scraper_new_urls');
        });
    }
}
