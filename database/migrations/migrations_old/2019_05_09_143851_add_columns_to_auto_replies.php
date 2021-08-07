<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToAutoReplies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auto_replies', function (Blueprint $table) {
          $table->string('type')->after('id');
          $table->datetime('sending_time')->nullable()->after('reply');
          $table->string('repeat')->nullable()->after('sending_time');

          $table->string('keyword')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auto_replies', function (Blueprint $table) {
          $table->dropColumn('type');
          $table->dropColumn('sending_time');
          $table->dropColumn('repeat');

          $table->string('keyword')->change();
        });
    }
}
