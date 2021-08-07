<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToAffilateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('affiliates', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('url')->nullable();
            $table->string('website_name')->nullable();
            $table->string('unique_visitors_per_month')->nullable();
            $table->string('page_views_per_month')->nullable();
            $table->string('city')->nullable();
            $table->integer('postcode')->nullable();
            $table->string('country')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('affiliates', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('url');
            $table->dropColumn('website_name');
            $table->dropColumn('unique_visitors_per_month');
            $table->dropColumn('page_views_per_month');
            $table->dropColumn('city');
            $table->dropColumn('postcode');
            $table->dropColumn('country');
        });
    }
}
