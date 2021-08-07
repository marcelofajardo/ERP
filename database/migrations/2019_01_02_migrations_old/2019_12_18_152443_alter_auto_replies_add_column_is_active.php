<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAutoRepliesAddColumnIsActive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auto_replies', function (Blueprint $table) {
            $table->integer('is_active')->default('0')->after('repeat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auto_replies', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
}
