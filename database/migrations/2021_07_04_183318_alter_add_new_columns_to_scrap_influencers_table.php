<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddNewColumnsToScrapInfluencersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scrap_influencers', function (Blueprint $table) {
            $table->text('profile_pic')->nullable();
            $table->text('friends')->nullable();
            $table->text('cover_photo')->nullable();
            $table->text('interests')->nullable();
            $table->string('work_at')->nullable();
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
            $table->dropField("profile_pic");
            $table->dropField("friends");
            $table->dropField("cover_photo");
            $table->dropField("interests");
            $table->dropField("work_at");
        });
    }
}
