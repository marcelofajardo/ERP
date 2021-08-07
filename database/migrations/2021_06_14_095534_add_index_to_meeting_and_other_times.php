<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToMeetingAndOtherTimes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meeting_and_other_times', function(Blueprint $table)
        {
            $table->index(['model_id','model','user_id','approve']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meeting_and_other_times', function(Blueprint $table)
        {
            $table->dropIndex(['model_id','model','user_id','approve']);
        });
    }
}
