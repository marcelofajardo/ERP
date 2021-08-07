<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToDevloperTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('developer_tasks', function(Blueprint $table)
        {
            $table->index(['status','assigned_to','tester_id','team_lead_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('developer_tasks', function(Blueprint $table)
        {
            $table->dropIndex(['status','assigned_to','tester_id','team_lead_id']);
        });
    }
}
