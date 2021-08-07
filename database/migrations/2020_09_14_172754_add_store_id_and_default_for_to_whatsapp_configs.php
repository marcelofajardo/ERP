<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStoreIdAndDefaultForToWhatsappConfigs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('whatsapp_configs', function (Blueprint $table) {
            $table->integer('store_website_id')->nullable();
			$table->integer('default_for')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('whatsapp_configs', function (Blueprint $table) {
            $table->dropColumn(['store_website_id']);
            $table->dropColumn(['default_for']);
        });
    }
}
