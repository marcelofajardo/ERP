<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyfieldTableGooglecamp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::table('googlecampaigns', function (Blueprint $table) {
            $table->string('status')->nullable()->comment('UNKNOWN, ENABLED,PAUSED,REMOVED')->change();
        });*/
        \DB::statement("ALTER TABLE `googlecampaigns` CHANGE `status` `status` VARCHAR(255) NULL DEFAULT NULL COMMENT 'UNKNOWN, ENABLED,PAUSED,REMOVED';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('googlecampaigns', function (Blueprint $table) {
            //
        });
    }
}
