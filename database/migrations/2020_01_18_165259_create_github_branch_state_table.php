<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGithubBranchStateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('github_branch_states', function (Blueprint $table) {
            //
            $table->integer('repository_id');
            $table->string('branch_name');
            $table->integer('ahead_by');
            $table->integer('behind_by');
            $table->timestamps();

            $table->primary(['repository_id', 'branch_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('github_branch_states');
    }
}
