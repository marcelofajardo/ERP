<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponCodeRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_code_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('is_active')->default(0);
            $table->integer('times_used')->default(0);
            $table->string('website_ids');
            $table->string('customer_group_ids');
            $table->string('coupon_type');
            $table->text('coupon_code');
            $table->integer('use_auto_generation')->default(0);
            $table->string('uses_per_coupon');
            $table->string('uses_per_coustomer');
            $table->string('store_website_id')->nullable();
            $table->integer('is_rss')->default(0);
            $table->string('priority');
            $table->date('from_date');
            $table->date('to_date');
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
        Schema::dropIfExists('coupon_code_rules');
    }
}
