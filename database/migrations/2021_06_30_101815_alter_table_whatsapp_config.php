<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableWhatsappConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('whatsapp_configs',function($table) {
            $table->integer("is_use_own")->default(0)->after('store_website_id');
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
        Schema::table('whatsapp_configs',function($table) {
            $table->dropField("is_use_own");
        });
    }
}
