<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterStoreWebsiteUserPassword extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_websites', function (Blueprint $table) {
            $table->string('magento_url')->nullable()->after("remote_software");
            $table->string('magento_username')->nullable()->after("magento_url");
            $table->string('magento_password')->nullable()->after("magento_username");
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
            $table->dropColumn('magento_url');
            $table->dropColumn('magento_username');
            $table->dropColumn('magento_password');
        });
    }

}
