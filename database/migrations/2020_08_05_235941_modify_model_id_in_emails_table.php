<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyModelIdInEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::table('emails', function (Blueprint $table) {
            $table->integer('model_id')->unsigned()->nullable()->change();
        });*/
        \DB::statement("ALTER TABLE `emails` CHANGE `model_id` `model_id` INT(10) UNSIGNED NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::table('emails', function (Blueprint $table) {
            $table->integer('model_id')->unsigned()->change();
        });*/
    }
}
