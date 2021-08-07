<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnLanguageInDevelopmentTask extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('developer_tasks', function (Blueprint $table) {
            $table->string('language')->after('responsible_user_id')->nullable();
            $table->integer('master_user_id')->after('responsible_user_id')->default(0);
            $table->integer('master_user_priority')->after('master_user_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('developer_tasks', function (Blueprint $table) {
            $table->dropColumn('language');
            $table->dropColumn('master_user_id');
            $table->dropColumn('master_user_priority');
        });
    }
}
