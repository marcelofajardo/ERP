<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDeveloperTasksFieldScraper extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table("developer_tasks",function(Blueprint $table) {
            $table->integer("scraper_id")->nullable()->after("tester_hubstaff_task_id");
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
        Schema::table("developer_tasks",function(Blueprint $table) {
            $table->dropField("scraper_id");
        });
    }
}
