<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterHubstaffActivitySummaries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hubstaff_activity_summaries', function (Blueprint $table) {
            $table->integer("pending")->nullable();
            $table->longText("pending_ids")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hubstaff_activity_summaries', function (Blueprint $table) {
            $table->dropField("pending");
            $table->dropField("pending_ids");
        });
    }
}
