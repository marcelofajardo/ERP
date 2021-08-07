<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHubStaffUserIdAndTotalTrackHubStaffActivityNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hubstaff_activity_notifications', function (Blueprint $table) {
            $table->string('hubstaff_user_id')->after('user_id')->nullable();
            $table->string('total_track')->after('hubstaff_user_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hubstaff_activity_notifications', function (Blueprint $table) {
            //
        });
    }
}
