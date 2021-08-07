<?php

use Illuminate\Database\Migrations\Migration;

class AlterTableSiteDevelopmentStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('ALTER TABLE `site_development_statuses` CHANGE `name` `name` VARCHAR(255) NULL DEFAULT NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
