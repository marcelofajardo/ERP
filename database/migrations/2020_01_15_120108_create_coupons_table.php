<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('magento_id')->nullable();
            $table->string('code', 50);
            $table->text('description')->nullable();
            $table->dateTime('start');
            $table->dateTime('expiration')->nullable();
            $table->text('details')->nullable();
            $table->string('currency', 10)->nullable();
            $table->double('discount_fixed', 8, 2)->default(0);
            $table->double('discount_percentage', 8, 2)->default(0);
            $table->unsignedSmallInteger('minimum_order_amount')->default(0);
            $table->unsignedSmallInteger('maximum_usage')->nullable();
            $table->unsignedSmallInteger('usage_count')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
