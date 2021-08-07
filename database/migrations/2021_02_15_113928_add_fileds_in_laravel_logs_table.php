<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFiledsInLaravelLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laravel_logs', function (Blueprint $table) {
            $table->string('module_name')->nullable()->after('website');
            $table->string('controller_name')->nullable()->after('module_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laravel_logs', function (Blueprint $table) {
            $table->dropColumn('module_name');
            $table->dropColumn('controller_name');
        });
    }
}
