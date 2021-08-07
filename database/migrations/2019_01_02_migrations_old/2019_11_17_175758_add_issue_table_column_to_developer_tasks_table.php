<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIssueTableColumnToDeveloperTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('developer_tasks', function (Blueprint $table) {
            $table->tinyInteger('is_resolved')->default(0);
            //$table->integer('submitted_by');
            $table->integer('responsible_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('developer_tasks', function (Blueprint $table) {
            $table->dropColumn('is_resolved');
            //$table->dropColumn('submitted_by');
            $table->dropColumn('responsible_user_id');
        });
    }
}
