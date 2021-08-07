<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserDetailsInZoomMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zoom_meetings', function (Blueprint $table) {
             $table->dropColumn('customer_id');
             $table->integer('user_id')->after('host_zoom_id')->nullable();
             $table->string('user_type')->after('user_id')->nullable();
             $table->string('timezone')->after('meeting_duration')->nullable();
             $table->dateTime('start_date_time')->change();
             $table->string('zoom_recording')->change()->nullable();
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
            $table->dropColumn('user_id');
            $table->dropColumn('user_type');
            $table->dropColumn('timezone');
            $table->date('start_date_time')->change();
            $table->integer('customer_id')->after('host_zoom_id')->nullable();;
        });
    }
}
