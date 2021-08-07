<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterScrapedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scraped_products', function (Blueprint $table) {
            $table->string('ip_address')->nullable()->after('discounted_price');
            $table->integer('category')->nullable()->after('ip_address');
            $table->integer('validated')->nullable()->after('category');
            $table->text('validation_result')->nullable()->after('validated');
            $table->text('raw_data')->nullable()->after('validation_result');
            $table->integer('cron_executed')->after('raw_data')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scraped_products', function (Blueprint $table) {
            $table->dropColumn('ip_address');
            $table->dropColumn('category');
            $table->dropColumn('validated');
            $table->dropColumn('validation_result');
            $table->dropColumn('raw_data');
        });
    }
}
