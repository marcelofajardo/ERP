<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBoxDimensionsToWaybills extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('waybills', function (Blueprint $table) {
          $table->double('box_length', 8, 2)->after('awb');
          $table->double('box_width', 8, 2)->after('box_length');
          $table->double('box_height', 8, 2)->after('box_width');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('waybills', function (Blueprint $table) {
          $table->dropColumn('box_length');
          $table->dropColumn('box_width');
          $table->dropColumn('box_height');
        });
    }
}
