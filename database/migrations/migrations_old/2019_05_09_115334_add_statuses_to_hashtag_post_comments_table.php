<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusesToHashtagPostCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hashtag_post_comments', function (Blueprint $table) {
            $table->softDeletes();
            $table->integer('review_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hashtag_post_comments', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('review_id');
        });
    }
}
