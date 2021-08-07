<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGenderColumnInDifferentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('gender')->default('all');
        });
        Schema::table('instagram_auto_comments', function (Blueprint $table) {
            $table->string('gender')->default('all');
        });
        Schema::table('auto_comment_histories', function (Blueprint $table) {
            $table->string('gender')->default('all');
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
            $table->dropColumn('gender');
        });
        Schema::table('instagram_auto_comments', function (Blueprint $table) {
            $table->dropColumn('gender');
        });
        Schema::table('auto_comment_histories', function (Blueprint $table) {
            $table->dropColumn('gender');
        });
    }
}
