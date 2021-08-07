<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRenameColumnNameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //\DB::statement("ALTER TABLE `tasks` CHANGE `reminder_from-` `reminder_from` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00'");
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
