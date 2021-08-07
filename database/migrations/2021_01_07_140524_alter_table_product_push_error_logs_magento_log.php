<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableProductPushErrorLogsMagentoLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('product_push_error_logs',function(Blueprint $table) {
            $table->integer('log_list_magento_id')->nullable()->after('url');
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
        Schema::table('product_push_error_logs',function(Blueprint $table) {
            $table->dropField('log_list_magento_id');
        });
    }
}
