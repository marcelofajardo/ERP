<?php

use Illuminate\Database\Migrations\Migration;

class AddScrapedProductsIndexTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE `scraped_products` CHANGE `url` `url` VARCHAR(1025) NULL DEFAULT NULL;");
        \DB::statement("ALTER TABLE `scraped_products` ADD INDEX(`url`);");
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
