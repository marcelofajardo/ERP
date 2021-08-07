<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostsFollowesFollowingLocationInInstagramUsersListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instagram_users_lists', function (Blueprint $table) {
            $table->integer('posts')->nullable()->after('because_of');
            $table->integer('followers')->nullable()->after('because_of');
            $table->integer('following')->nullable()->after('because_of');
            $table->string('location')->nullable()->after('because_of');
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
            $table->dropColumn('posts');
            $table->dropColumn('followers');
            $table->dropColumn('following');
            $table->dropColumn('location');
        });
    }
}
