<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyModelTypeInEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::table('emails', function (Blueprint $table) {
            $table->string('model_type')->nullable()->change();
        });*/
        \DB::statement("ALTER TABLE `emails` CHANGE `model_type` `model_type` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::table('emails', function (Blueprint $table) {
            $table->string('model_type')->change();
        });*/
    }
}
