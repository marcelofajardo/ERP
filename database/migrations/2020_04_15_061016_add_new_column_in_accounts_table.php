<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnInAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('number')->after('password');
            $table->string('provider')->after('password')->nullable();
            $table->integer('is_customer_support')->after('password')->default(0);
            $table->integer('frequency')->after('password')->nullable();
            $table->dateTime('last_online')->after('password')->nullable();
            $table->integer('is_connected')->after('password')->default(0);
            $table->integer('send_start')->after('password');
            $table->integer('send_end')->after('password');
            $table->string('instance_id')->nullable()->after('password');
            $table->integer('status')->default(0)->after('password');
            $table->string('token')->nullable()->after('password');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('number');
            $table->dropColumn('provider');
            $table->dropColumn('is_customer_support');
            $table->dropColumn('frequency');
            $table->dropColumn('last_online');
            $table->dropColumn('is_connected');
            $table->dropColumn('send_start');
            $table->dropColumn('send_end');
            $table->dropColumn('instance_id');
            $table->dropColumn('status');
            $table->dropColumn('token');
        });
    }
}
