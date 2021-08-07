<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErpAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('erp_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table');
            $table->integer('row_id')->nullable();
            $table->integer('transacted_by');
            $table->decimal('debit')->default(0);
            $table->decimal('credit')->default(0);
            $table->integer('user_id')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->longText('metadata')->nullable();
            $table->text('remark');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('erp_accounts');
    }
}
