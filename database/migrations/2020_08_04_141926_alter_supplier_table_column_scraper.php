<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSupplierTableColumnScraper extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE `suppliers` CHANGE `scrapper` `scrapper` VARCHAR(191) NULL;");
        \DB::statement("update suppliers set scrapper = null where scrapper =''");
        \DB::statement("ALTER TABLE `suppliers` CHANGE `scrapper` `scrapper` INT(11) NULL DEFAULT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE `suppliers` CHANGE `scrapper` `scrapper` VARCHAR(191) NULL;");
    }
}
