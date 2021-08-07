<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableInfluencerKeywordAddFieldAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('influencer_keywords', function (Blueprint $table) {
            $table->integer('instagram_account_id')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('influencer_keywords', function (Blueprint $table) {
            $table->dropField('instagram_account_id');
        });
    }
}
