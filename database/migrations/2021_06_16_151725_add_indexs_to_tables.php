<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddIndexsToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::select("ALTER TABLE `product_suppliers` ADD INDEX(`supplier_id`);");
        DB::select("ALTER TABLE `scrapers` ADD INDEX(`supplier_id`);");
        DB::select("ALTER TABLE `categories` ADD INDEX(`parent_id`);");
        DB::select("ALTER TABLE `user_logins` ADD INDEX(`user_id`);");
        DB::select("ALTER TABLE `product_suppliers` ADD INDEX(`product_id`);");
        DB::select("ALTER TABLE `suppliers` ADD INDEX(`deleted_at`);"); 
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
