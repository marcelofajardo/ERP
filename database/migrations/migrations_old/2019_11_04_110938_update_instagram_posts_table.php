<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateInstagramPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instagram_posts',function($table){
            $table->integer('hashtag_id')->nullable()->after('id');
            $table->string('location')->nullable()->after('hashtag_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instagram_posts', function (Blueprint $table) {
            $table->dropColumn('hashtag_id');
            $table->dropColumn('location');
        });
    }
}
