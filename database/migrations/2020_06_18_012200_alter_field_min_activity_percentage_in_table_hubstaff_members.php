<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AlterFieldMinActivityPercentageInTableHubstaffMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hubstaff_members', function ($table) {
            $table->float("min_activity_percentage")->default("0.00")->after('currency');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hubstaff_members', function ($table) {
            $table->dropColumn("min_activity_percentage");
        });
    }
}
