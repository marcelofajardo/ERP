<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToBulkCustomerRepliesKeywordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
     
        DB::select("ALTER TABLE `bulk_customer_replies_keywords` CHANGE `value` `value` VARCHAR(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;");
        DB::select("ALTER TABLE `bulk_customer_replies_keywords` ADD INDEX(`value`);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bulk_customer_replies_keywords', function (Blueprint $table) {
            $table->dropIndex(['count','value']);
            $table->text('value')->change();


        });
    }
}
