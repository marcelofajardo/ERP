<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableScraperDeveloperFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table("scrapers",function(Blueprint $table) {
            $table->integer("developer_flag")->nullable()->default(0)->after('flag');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table("scrapers",function(Blueprint $table) {
            $table->dropField("developer_flag");
        });
    }
}
