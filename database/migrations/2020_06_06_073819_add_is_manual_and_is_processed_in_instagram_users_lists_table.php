<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsManualAndIsProcessedInInstagramUsersListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instagram_users_lists', function (Blueprint $table) {
            $table->integer('is_manual')->default('0')->after('posts');
            $table->integer('is_processed')->default('0')->after('posts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instagram_users_lists', function (Blueprint $table) {
            $table->dropColumn('is_manual');
            $table->dropColumn('is_processed');
        });
    }
}
