<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferenceColumnInDeveloperTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('developer_tasks', function (Blueprint $table) {
            $table->text('reference')->nullable()->after('is_resolved');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('developer_tasks', function (Blueprint $table) {
            $table->dropColumn('reference');
        });
    }
}
