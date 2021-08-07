<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsToScrapInfluencersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scrap_influencers',function($table){
            $table->string('email')->nullable()->after('posts');
            $table->string('country')->nullable()->after('posts');
            $table->string('facebook')->nullable()->after('posts');
            $table->string('twitter')->nullable()->after('posts');
            $table->string('website')->nullable()->after('posts');
            $table->string('phone')->nullable()->after('posts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scrap_influencers',function($table){
            $table->dropColumn('email');
            $table->dropColumn('country');
            $table->dropColumn('facebook');
            $table->dropColumn('twitter');
            $table->dropColumn('website');
            $table->dropColumn('phone'); 
        });
    }
}
