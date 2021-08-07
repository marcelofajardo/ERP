<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddColumnToSuggestedProductsTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suggested_products', function (Blueprint $table) {
            $table->integer('number')->default(5)->nullable()->after('customer_id');
            $table->integer('chat_message_id')->nullable()->after('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suggested_products', function (Blueprint $table) {
            $table->dropField('number');
            $table->dropField('chat_message_id');
        });
    }
}
