<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsInUserAvaibilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_avaibilities', function (Blueprint $table) {
            $table->decimal('day',4,2)->default(0)->after('to');
            $table->integer('minute')->default(0)->after('day');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_avaibilities', function (Blueprint $table) {
            $table->dropField('day');
            $table->dropField('minute');
        });
    }
}
