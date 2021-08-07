<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AlterFieldUserIdInHubstaffMember extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('ALTER TABLE `hubstaff_members` MODIFY `user_id` INTEGER UNSIGNED NULL;');
        \DB::statement('UPDATE `hubstaff_members` SET `user_id` = NULL WHERE `user_id` = 0;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('UPDATE `hubstaff_members` SET `user_id` = 0 WHERE `user_id` IS NULL;');
        \DB::statement('ALTER TABLE `hubstaff_members` MODIFY `user_id` INTEGER UNSIGNED NOT NULL;');
    }
}
