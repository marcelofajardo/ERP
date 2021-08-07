<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateErpLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('erp_leads', function (Blueprint $table) {
            //DB::statement('ALTER TABLE `erp_leads` ADD `deleted_at` TIMESTAMP NULL DEFAULT NULL AFTER `gender`;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('erp_leads', function (Blueprint $table) {
            //DB::statement('ALTER TABLE `erp_leads` DROP `deleted_at`;');
        });
    }
}
