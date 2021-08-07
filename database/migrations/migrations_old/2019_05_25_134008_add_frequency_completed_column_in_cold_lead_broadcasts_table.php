<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFrequencyCompletedColumnInColdLeadBroadcastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cold_lead_broadcasts', function (Blueprint $table) {
            $table->integer('frequency_completed')->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cold_lead_broadcasts', function (Blueprint $table) {
            $table->dropColumn('frequency_completed');
        });
    }
}
