<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHubstaffUserEmailColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('hubstaff_members', function (Blueprint $table) {
            $table->string('email')->nullable()->after('hubstaff_user_id');
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
        Schema::table('hubstaff_members', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
}
