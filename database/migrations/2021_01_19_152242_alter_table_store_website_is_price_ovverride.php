<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableStoreWebsiteIsPriceOvverride extends Migration
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
            $table->integer('is_price_override')->default(0)->nullable()->after('country_duty');
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
            $table->dropField('is_price_override');
        });
    }
}
