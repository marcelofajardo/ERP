<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDigitalMarketingFilesStorage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('digital_marketing_platform_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('digital_marketing_platform_id');
            $table->integer('user_id');
            $table->longText('file_name');
            $table->timestamps();
        });

        Schema::create('digital_marketing_solution_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('digital_marketing_solution_id');
            $table->integer('user_id');
            $table->longText('file_name');
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
        Schema::dropIfExists('digital_marketing_platform_files');
        Schema::dropIfExists('digital_marketing_solution_files');
    }
}
