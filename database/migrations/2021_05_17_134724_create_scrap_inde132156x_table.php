<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScrapInde132156xTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scrapers', function(Blueprint $table)
        {
            //$table->index('supplier_id');
            //$table->index('scraper_name');
            //$table->index('scraper_priority');
            //$table->index('scraper_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scrapers', function (Blueprint $table)
        {
            //$table->dropIndex(['supplier_id','scraper_name','scraper_priority','scraper_name']);
        });
    }
}
