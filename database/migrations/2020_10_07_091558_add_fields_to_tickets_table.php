<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('type_of_inquiry')->nullable();
            $table->string('last_name')->nullable();
            $table->string('country')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('order_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('type_of_inquiry');
            $table->dropColumn('country');
            $table->dropColumn('phone_no');
            $table->dropColumn('order_no');
            $table->dropColumn('last_name');
        });
    }
}
