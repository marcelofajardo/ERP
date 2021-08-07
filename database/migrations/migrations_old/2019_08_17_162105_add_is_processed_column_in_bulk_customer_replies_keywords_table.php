<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsProcessedColumnInBulkCustomerRepliesKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bulk_customer_replies_keywords', function (Blueprint $table) {
            $table->boolean('is_processed')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bulk_customer_replies_keywords', function (Blueprint $table) {
            $table->dropColumn('is_processed');
        });
    }
}
