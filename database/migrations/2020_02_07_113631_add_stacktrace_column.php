<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStacktraceColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laravel_github_logs', function (Blueprint $table) {
            $table->text('stacktrace')->nullable();
            $table->string('commit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laravel_github_logs', function (Blueprint $table) {
            $table->dropColumn('stacktrace');
            $table->dropColumn('commit');
        });
    }
}
