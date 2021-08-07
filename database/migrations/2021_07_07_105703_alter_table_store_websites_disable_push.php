<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableStoreWebsitesDisablePush extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('store_websites',function(Blueprint $table) {
            $table->integer("disable_push")->default(0)->after("is_published");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('store_websites',function(Blueprint $table) {
            $table->dropField("disable_push");
        });
    }
}
