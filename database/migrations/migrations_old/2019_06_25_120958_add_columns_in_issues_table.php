<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->string('module');
            $table->integer('responsible_user_id')->nullable();
            $table->date('resolved_at')->nullable();
            $table->boolean('is_resolved')->default(0);
            $table->integer('submitted_by')->nullable();
            $table->decimal('cost')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->dropColumn([
                'module',
                'responsible_user_id',
                'resolved_at',
                'is_resolved',
                'submitted_by',
                'cost'
            ]);
        });
    }
}
