<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataToScheduledMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scheduled_messages', function (Blueprint $table) {
          $table->text('type')->nullable()->after('message');
          $table->text('data')->nullable()->after('type');
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
          $table->dropColumn('type');
          $table->dropColumn('data');
        });
    }
}
