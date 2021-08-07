<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditStoreWebsiteTableForSomeFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_websites', function (Blueprint $table) {
            $table->string('server_ip')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('staging_username')->nullable();
            $table->string('staging_password')->nullable();
            $table->string('mysql_username')->nullable();
            $table->string('mysql_password')->nullable();
            $table->string('mysql_staging_username')->nullable();
            $table->string('mysql_staging_password')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_websites', function (Blueprint $table) {
            //
        });
    }
}
