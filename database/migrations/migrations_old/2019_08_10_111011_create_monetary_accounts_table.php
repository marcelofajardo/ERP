<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonetaryAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monetary_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date')->nullable();
            $table->integer('currency')->default(1);
            $table->decimal('amount',13,4)->nullable();
            $table->string('type')->default('cash');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->mediumText('short_note')->nullable();
            $table->text('description')->nullable();
            $table->text('other')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('monetary_accounts');
    }
}
