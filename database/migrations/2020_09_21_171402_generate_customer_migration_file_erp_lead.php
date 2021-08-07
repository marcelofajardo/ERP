<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GenerateCustomerMigrationFileErpLead extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'is_blocked_lead')) {
                $table->integer('is_blocked_lead')->default(0)->nullable()->after("do_not_disturb");
            }
            if (!Schema::hasColumn('customers', 'lead_product_freq')) {
                $table->integer('lead_product_freq')->default(0)->nullable()->after("is_blocked_lead");
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
        //
    }
}
