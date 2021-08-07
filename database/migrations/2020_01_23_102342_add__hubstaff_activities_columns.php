<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHubstaffActivitiesColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hubstaff_activities', function (Blueprint $table) {
            $table->integer('keyboard');
            $table->integer('mouse');
            $table->integer('overall');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hubstaff_activities', function (Blueprint $table) {
            $table->dropColumn('keyboard');
            $table->dropColumn('mouse');
            $table->dropColumn('overall');
        });
    }
}
