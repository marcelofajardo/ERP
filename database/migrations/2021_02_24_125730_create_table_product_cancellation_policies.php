<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProductCancellationPolicies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_cancellation_policies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_website_id');
            $table->longText('message')->nullable();
            $table->integer('days_cancelation')->nullable();
            $table->integer('days_refund')->nullable();
            $table->integer('percentage')->nullable();
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
        Schema::dropIfExists('product_cancellation_policies');
    }
}
