<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCustomerIdForScheduledMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scheduled_messages', function (Blueprint $table) {
          $table->dropForeign('scheduled_messages_customer_id_foreign');
          $table->dropColumn('customer_id');
          // $table->integer('customer_id')->unsigned()->nullable()->change('user_id');
          //
          // $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scheduled_messages', function (Blueprint $table) {
          $table->integer('customer_id')->unsigned()->after('user_id');

          $table->foreign('customer_id')->references('id')->on('customers');
        });
    }
}
