<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSendingTimeToBroadcastImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('broadcast_images', function (Blueprint $table) {
          $table->datetime('sending_time')->nullable()->after('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('broadcast_images', function (Blueprint $table) {
          $table->dropColumn('sending_time');
        });
    }
}
