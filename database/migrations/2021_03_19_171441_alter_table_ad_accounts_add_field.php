<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableAdAccountsAddField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table("ad_accounts",function(Blueprint $table) {
            $table->string("last_error")->nullable()->after("status");
            $table->timestamp("last_error_at")->nullable()->after("last_error");
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
        Schema::table("ad_accounts",function(Blueprint $table) {
            $table->dropField("last_error");
            $table->dropField("last_error_at");
        });
    }
}
