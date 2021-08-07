<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWhatsappConfigTableDateAddStatusDeviceNameSimOwnerColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('whatsapp_configs', function($table){
            $table->string('device_name')->nullable();
            $table->string('simcard_number')->nullable();
            $table->string('simcard_owner')->nullable();
            $table->string('payment')->nullable();
            $table->date('recharge_date')->nullable();
            
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
            $table->dropColumn('device_name');
            $table->dropColumn('simcard_number');
            $table->dropColumn('simcard_owner');
            $table->dropColumn('payment');
            $table->dropColumn('recharge_date');
           
        });
    }
}
