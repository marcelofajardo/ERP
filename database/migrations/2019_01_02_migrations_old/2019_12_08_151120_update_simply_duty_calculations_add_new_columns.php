<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSimplyDutyCalculationsAddNewColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('simply_duty_calculations', function ($table) {
           $table->string('value')->after('id'); 
           $table->string('duty')->after('id');
           $table->string('duty_hscode')->after('id'); 
           $table->string('duty_type')->after('id');
           $table->string('shipping')->after('id');
           $table->string('insurance')->after('id');
           $table->string('total')->after('id');
           $table->string('exchange_rate')->after('id');
           $table->string('currency_type_origin')->after('id');
           $table->string('currency_type_destination')->after('id');
           $table->string('duty_minimis')->after('id');
           $table->string('vat_minimis')->after('id');
           $table->string('vat_rate')->after('id');
           $table->string('vat')->after('id');
           $table->dropColumn('hscode');
           $table->dropColumn('origin_country');
           $table->dropColumn('destination_country');
           $table->dropColumn('monetary_amount');
           $table->dropColumn('weight_per_monetary_amount');
           $table->dropColumn('max_duty_rate');
           $table->dropColumn('max_monetary_amount');
           $table->dropColumn('max_weight_per_monetary_amount');
           $table->dropColumn('min_duty_rate');
           $table->dropColumn('min_monetary_amount');
           $table->dropColumn('min_weight_per_monetary_amount');
           $table->dropColumn('weight_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('simply_duty_calculations', function ($table) {
            
        });
    }
}
