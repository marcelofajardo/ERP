<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableScraperServerHistoryAddField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('scraper_server_status_histories',function(Blueprint $table) {
            $table->string("total_memory")->after("server_id")->nullable();
            $table->string("used_memory")->after("total_memory")->nullable();
            $table->string("in_percentage")->after("used_memory")->nullable();
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
        Schema::table('scraper_server_status_histories',function(Blueprint $table) {
            $table->dropField("total_memory");
            $table->dropField("used_memory");
            $table->dropField("in_percentage");
        });
    }
}
