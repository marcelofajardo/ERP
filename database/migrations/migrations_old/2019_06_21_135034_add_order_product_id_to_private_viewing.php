<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderProductIdToPrivateViewing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('private_views', function (Blueprint $table) {
          $table->integer('order_product_id')->unsigned()->nullable()->after('assigned_user_id');
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
          $table->dropColumn('order_product_id');
        });
    }
}
