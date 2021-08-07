<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWhatsappConfigAddLastOnlineAndStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('whatsapp_configs', function($table){
            $table->dateTime('last_online')->nullable()->after('is_customer_support');
            // $table->tinyInteger('status')->default(0)->after('is_customer_support');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('whatsapp_configs', function($table){
            $table->dropColumn('last_online');
            // $table->dropColumn('status');
        });
    }
}
