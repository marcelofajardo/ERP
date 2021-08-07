<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultipleColumnsInScrapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scrapers', function (Blueprint $table) {
            $table->string('run_gap')->after('next_step_in_product_flow')->default(24);
            $table->string('time_out')->after('next_step_in_product_flow');
            $table->string('starting_urls')->after('next_step_in_product_flow');
            $table->string('designer_url_selector')->nullable()->after('next_step_in_product_flow');
            $table->string('product_url_selector')->after('next_step_in_product_flow');
            $table->dateTime('start_time')->after('next_step_in_product_flow');
            $table->dateTime('end_time')->after('next_step_in_product_flow');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scrapers', function (Blueprint $table) {
            $table->dropColumn('run_gap');
            $table->dropColumn('time_out');
            $table->dropColumn('starting_urls');
            $table->dropColumn('designer_url_selector');
            $table->dropColumn('product_url_selector');
            $table->dropColumn('start_time');
            $table->dropColumn('end_time');
        });
    }
}
