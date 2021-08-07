<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
          $table->integer('account_id')->unsigned()->nullable()->after('review_schedule_id');
          $table->integer('customer_id')->nullable()->after('account_id');
          $table->string('status')->nullable()->change();
          $table->integer('is_approved')->unsigned()->default(0)->after('status');
          $table->datetime('posted_date')->nullable()->after('is_approved');
          $table->string('review_link')->nullable()->after('posted_date');

          $table->foreign('account_id')->references('id')->on('accounts');
          $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
          $table->dropForeign('account_id');
          $table->dropForeign('customer_id');
          $table->integer('status')->default(0)->change();
          $table->dropColumn('is_approved');
          $table->dropColumn('posted_date');
          $table->dropColumn('review_link');
        });
    }
}
