<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsCountToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
          $table->integer('followers_count')->nullable()->after('platform');
          $table->integer('posts_count')->nullable()->after('followers_count');
          $table->integer('dp_count')->nullable()->after('posts_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {
          $table->dropColumn('followers_count');
          $table->dropColumn('posts_count');
          $table->dropColumn('dp_count');
        });
    }
}
