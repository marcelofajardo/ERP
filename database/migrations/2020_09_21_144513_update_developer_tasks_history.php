<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDeveloperTasksHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        \DB::statement("ALTER TABLE `developer_tasks_history` CHANGE `old_value` `old_value` VARCHAR(255) NULL DEFAULT NULL;");
        \DB::statement("ALTER TABLE `developer_tasks_history` CHANGE `new_value` `new_value` VARCHAR(255) NULL DEFAULT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
