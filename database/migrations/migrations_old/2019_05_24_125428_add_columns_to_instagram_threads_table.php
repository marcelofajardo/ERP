<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToInstagramThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instagram_threads', function (Blueprint $table) {
            $table->string('thread_id')->nullable()->change();
            $table->integer('customer_id')->nullable()->change();
            $table->integer('cold_lead_id')->nullable();
            $table->integer('account_id')->nullable();
            $table->dateTime('last_message_at')->nullable();
            $table->text('last_message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instagram_threads', function (Blueprint $table) {
            $table->dropColumn(['cold_lead_id', 'account_id', 'last_message_at', 'last_message']);
        });
    }
}
