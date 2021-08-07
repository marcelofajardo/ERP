<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreColumnsToReviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('review_schedules', function (Blueprint $table) {
          $table->integer('account_id')->unsigned()->nullable()->after('id');
          $table->datetime('posted_date')->nullable()->after('date');
          $table->string('review_link')->nullable()->after('review_count');

          $table->foreign('account_id')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('review_schedules', function (Blueprint $table) {
          $table->dropForeign(['account_id']);
          $table->dropColumn('posted_date');
          $table->dropColumn('review_link');
        });
    }
}
