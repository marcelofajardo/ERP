<?php


use Illuminate\Database\Migrations\Migration;

class AddIndexToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::select("ALTER TABLE `hubstaff_activities` ADD INDEX(`starts_at`);");
        DB::select("ALTER TABLE `hubstaff_members` ADD INDEX(`hubstaff_user_id`);");
        DB::select("ALTER TABLE `hubstaff_members` ADD INDEX(`user_id`);");
        DB::select("ALTER TABLE `developer_tasks` ADD INDEX(`hubstaff_task_id`);");
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
