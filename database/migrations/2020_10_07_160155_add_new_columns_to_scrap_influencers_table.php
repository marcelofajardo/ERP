<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsToScrapInfluencersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scrap_influencers', function (Blueprint $table) {
            $table->integer('post_id')->nullable();
            $table->string('post_caption')->nullable();
            $table->integer('instagram_user_id')->nullable();
            $table->string('post_media_type')->nullable();
            $table->string('post_code')->nullable();
            $table->string('post_location')->nullable();
            $table->integer('post_hashtag_id')->nullable();
            $table->integer('post_likes')->nullable();
            $table->integer('post_comments_count')->nullable();
            $table->text('post_media_url')->nullable();
            $table->string('posted_at')->nullable();
            $table->integer('comment_user_id')->nullable();
            $table->string('comment_user_full_name')->nullable();
            $table->string('comment_username')->nullable();
            $table->integer('instagram_post_id')->nullable();
            $table->integer('comment_id')->nullable();
            $table->text('comment')->nullable();
            $table->text('comment_profile_pic_url')->nullable();
            $table->timestamp('comment_posted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scrap_influencers', function (Blueprint $table) {
            //
        });
    }
}
