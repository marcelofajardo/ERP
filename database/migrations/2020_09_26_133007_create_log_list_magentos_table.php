<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateLogListMagentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `log_list_magentos` ADD `sync_status` ENUM('success', 'error') NULL DEFAULT NULL AFTER `store_website_id`");
        DB::statement("ALTER TABLE `log_list_magentos` ADD `languages` TEXT NULL DEFAULT NULL COMMENT 'Language Id (JSON)' AFTER `sync_status`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('log_list_magentos');
    }
}
