<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHubstaffProjectsTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hubstaff_projects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hubstaff_project_id');
            $table->integer('organisation_id');
            $table->string('hubstaff_project_name');
            $table->string('hubstaff_project_description');
            $table->string('hubstaff_project_status');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hubstaff_projects');
    }
}
