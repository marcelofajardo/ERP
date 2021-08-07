<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableWatsonAccountField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('watson_accounts',function(Blueprint $table) {
            $table->string('user_name')->nullable()->after('assistant_id');
            $table->string('password')->nullable()->after('user_name');
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
        Schema::table('watson_accounts',function(Blueprint $table) {
            $table->dropField('user_name');
            $table->dropField('password');
        });
    }
}
