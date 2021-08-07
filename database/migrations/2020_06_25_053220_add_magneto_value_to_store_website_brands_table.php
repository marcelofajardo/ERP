<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMagnetoValueToStoreWebsiteBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_website_brands', function (Blueprint $table) {
            $table->string('magento_value')->after('markup')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_website_brands', function (Blueprint $table) {
            $table->dropColumn('magento_value');
        }); 
    }
}
