<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectFileManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_file_managers', function (Blueprint $table) {
            $table->increments('id');
            $table->String('name')->nullable();
            $table->String('project_name')->nullable();
            $table->String('size')->nullable();
            $table->dateTime('notification_at')->nullable();
            $table->String('parent')->nullable();
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
        Schema::dropIfExists('project_file_managers');
    }
}
