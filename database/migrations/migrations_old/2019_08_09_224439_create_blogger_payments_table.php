<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBloggerPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogger_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('blogger_id')->unsigned();
            $table->foreign('blogger_id')->references('id')->on('bloggers');
            $table->integer('currency')->default(0);
            $table->date('payment_date')->nullable();
            $table->date('paid_date')->nullable();
            $table->decimal('payable_amount','13',4)->nullable();
            $table->decimal('paid_amount','13',4)->nullable();
            $table->text('description')->nullable();
            $table->text('other')->nullable();
            $table->boolean('status')->default(0);
            $table->integer('user_id')->nullable();
            $table->integer('updated_by')->nullable();
            $table->softDeletes();
            $table->index('status');
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
        Schema::dropIfExists('blogger_payments');
    }
}
