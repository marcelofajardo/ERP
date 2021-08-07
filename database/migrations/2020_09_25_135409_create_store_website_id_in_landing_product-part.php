<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreWebsiteIdInLandingProductPart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('landing_page_products', function (Blueprint $table) {
            if (!Schema::hasColumn('landing_page_products', 'store_website_id')) {
                $table->unsignedInteger('store_website_id')->nullable()->after('shopify_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('landing_page_products', function (Blueprint $table) {
            $table->dropField('store_website_id');
        });
    }
}
