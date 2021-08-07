<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountIdToThreads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('complaint_threads', function (Blueprint $table) {
          $table->integer('account_id')->unsigned()->nullable()->after('complaint_id');

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
        Schema::table('complaints_threads', function (Blueprint $table) {
          $table->dropForeign(['account_id']);
          $table->dropColumn('account_id');
        });
    }
}
