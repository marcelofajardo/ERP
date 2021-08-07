<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColumnInReturnExchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::table('return_exchanges', function (Blueprint $table) {
                DB::statement("ALTER TABLE
                    `return_exchanges`
                MODIFY COLUMN
                    `type` enum(
                        'return',
                        'exchange',
                        'buyback'
                    )
                NOT NULL");
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::table('return_exchanges', function (Blueprint $table) {
            $table->dropColumn('type');
        });*/
    }
}
