<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusFieldReturnExchange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('return_exchange_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('return_exchange_histories', 'status_id')) {
                $table->integer('status_id')->nullable()->index()->after("comment");
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('return_exchange_histories', function (Blueprint $table) {
            $table->dropColumn('status_id');
        });
    }
}
