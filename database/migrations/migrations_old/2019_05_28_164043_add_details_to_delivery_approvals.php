<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDetailsToDeliveryApprovals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_approvals', function (Blueprint $table) {
          $table->integer('private_view_id')->nullable()->after('order_id');
          $table->integer('assigned_user_id')->unsigned()->nullable()->after('private_view_id');
          $table->string('status')->nullable()->after('approved');
          $table->datetime('date')->nullable()->after('status');

          $table->foreign('private_view_id')->references('id')->on('private_views');
          $table->foreign('assigned_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_approvals', function (Blueprint $table) {
          $table->dropForeign(['private_view_id']);
          $table->dropForeign(['assigned_user_id']);

          $table->dropColumn('private_view_id');
          $table->dropColumn('assigned_user_id');
          $table->dropColumn('status');
          $table->dropColumn('date');
        });
    }
}
