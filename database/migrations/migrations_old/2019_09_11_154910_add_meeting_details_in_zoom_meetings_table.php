<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMeetingDetailsInZoomMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zoom_meetings', function (Blueprint $table) {
            $table->string('meeting_id')->after('id')->nullable();
            $table->string('join_meeting_url')->after('meeting_agenda')->nullable();
            $table->text('start_meeting_url')->after('join_meeting_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('zoom_meetings', function (Blueprint $table) {
            $table->dropColumn('meeting_id');
            $table->dropColumn('join_meeting_url');
            $table->dropColumn('start_meeting_url');
        });
    }
}
