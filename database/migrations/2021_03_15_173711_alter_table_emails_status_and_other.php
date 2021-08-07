<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableEmailsStatusAndOther extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table("emails",function(Blueprint $table) {
           $table->integer('store_website_id')->after('is_draft')->nullable()->index();
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
        Schema::table("emails",function(Blueprint $table) {
            $table->dropField('store_website_id');
        });
    }
}
