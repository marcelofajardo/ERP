<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyCashFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_flows', function (Blueprint $table) {
            $table->decimal('expected',13,4)->nullable();
            $table->decimal('actual',13,4)->nullable();
            $table->integer('cash_flow_able_id')->nullable();
            $table->string('cash_flow_able_type')->nullable();
            $table->tinyInteger('status')->default(0); //pending , complete & else to come
            $table->string('order_status')->nullable(); //various status of order
            $table->integer('updated_by')->unsigned()->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->tinyInteger('currency')->default(1);
            $table->date('date')->change();

            $table->index('status');
            $table->index('order_status');
            $table->index('currency');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cash_flows', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['order_status']);
            $table->dropIndex(['currency']);
            $table->dropColumn('expected');
            $table->dropColumn('actual');
            $table->dropColumn('cash_flow_able_id');
            $table->dropColumn('cash_flow_able_type');
            $table->dropColumn('status');
            $table->dropColumn('order_status');
            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');
            $table->dropColumn('currency');
        });
    }
}
