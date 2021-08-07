<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsintowaybillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('waybills', function (Blueprint $table) {
            $table->string('from_customer_id')->nullable()->after('awb');
            $table->string('from_customer_name')->nullable()->after('from_customer_id');
            $table->string('from_city')->nullable()->after('from_customer_name');
            $table->string('from_country_code')->nullable()->after('from_city');
            $table->string('from_customer_phone')->nullable()->after('from_country_code');
            $table->string('from_customer_address_1')->nullable()->after('from_customer_phone');
            $table->string('from_customer_address_2')->nullable()->after('from_customer_address_1');
            $table->string('from_customer_pincode')->nullable()->after('from_customer_address_2');
            $table->string('to_customer_id')->nullable()->after('from_customer_pincode');
            $table->string('to_customer_name')->nullable()->after('to_customer_id');
            $table->string('to_city')->nullable()->after('to_customer_name');
            $table->string('to_country_code')->nullable()->after('to_city');
            $table->string('to_customer_phone')->nullable()->after('to_country_code');
            $table->string('to_customer_address_1')->nullable()->after('to_customer_phone');
            $table->string('to_customer_address_2')->nullable()->after('to_customer_address_1');
            $table->string('to_customer_pincode')->nullable()->after('to_customer_address_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('waybills', function (Blueprint $table) {
            //
        });
    }
}
