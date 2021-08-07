<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToBranchStateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('github_branch_states', function (Blueprint $table) {
            $table->string('last_commit_author_username')->nullable();
            $table->dateTime('last_commit_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('github_branch_states', function (Blueprint $table) {
            //
            $table->dropColumn('last_commit_author_username');
            $table->dropColumn('last_commit_time');
        });
    }
}
