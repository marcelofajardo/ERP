<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContirbutionsToCustomerOrderCharitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_order_charities', function (Blueprint $table) {
			$table->Integer('customer_contribution');
			$table->Integer('our_contribution');
			$table->String('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_order_charities', function (Blueprint $table) {
            //
        });
    }
}
