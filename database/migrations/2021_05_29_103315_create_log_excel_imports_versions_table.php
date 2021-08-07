<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogExcelImportsVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_excel_import_versions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('filename')->nullable();
            $table->string('file_version')->nullable();
            $table->integer('log_excel_imports_id')->nullable();
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
        Schema::dropIfExists('log_excel_imports_versions');
    }
}
