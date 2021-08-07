<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserHubstaffRefreshToken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //create a new column to accomodate the Hubstaff refresh token
        Schema::table('users', function (Blueprint $table) {
            $table->string('refresh_token_hubstaff')->nullable()->after('auth_token_hubstaff');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //remove the new column which accomodates the Hubstaff refresh token
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('refresh_token_hubstaff');
        });
    }
}
