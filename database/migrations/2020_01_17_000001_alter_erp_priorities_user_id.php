<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterErpPrioritiesUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('erp_priorities', function (Blueprint $table) {
            $table->integer('user_id')->default(0)->after("model_type");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('erp_priorities', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
