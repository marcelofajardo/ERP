<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAssignedUserToPrivateViewing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('private_views', function (Blueprint $table) {
          $table->integer('assigned_user_id')->unsigned()->nullable()->after('customer_id');

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
        Schema::table('private_views', function (Blueprint $table) {
          $table->dropForeign(['assigned_user_id']);

          $table->dropColumn('assigned_user_id');
        });
    }
}
