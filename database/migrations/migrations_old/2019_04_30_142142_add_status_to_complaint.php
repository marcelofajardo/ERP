<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToComplaint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('complaints', function (Blueprint $table) {
          $table->string('status')->nullable()->after('link');
          $table->text('plan_of_action')->nullable()->after('status');
          $table->string('where')->nullable()->after('plan_of_action');
          $table->string('username')->nullable()->after('where');
          $table->string('name')->nullable()->after('username');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('complaints', function (Blueprint $table) {
          $table->dropColumn('status');
          $table->dropColumn('plan_of_action');
          $table->dropColumn('where');
          $table->dropColumn('username');
          $table->dropColumn('name');
        });
    }
}
