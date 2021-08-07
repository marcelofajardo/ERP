<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableTicketAddFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('tickets', function(Blueprint $table) {
            $table->string('brand')->nullable()->after('sku');
            $table->string('style')->nullable()->after('brand');
            $table->string('keyword')->nullable()->after('style');
            $table->string('image')->nullable()->after('keyword');
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
        Schema::table('tickets', function(Blueprint $table) {
            $table->dropField('brand');
            $table->dropField('style');
            $table->dropField('keyword');
            $table->dropField('image');
        });
    }
}
