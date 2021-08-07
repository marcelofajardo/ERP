<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerMarketingPlatformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_marketing_platforms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id');
            $table->integer('marketing_platform_id');
            $table->string('user_name')->nullable();
            $table->tinyInteger('active')->default(0);
            $table->text('remark')->nullable();
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
        Schema::dropIfExists('customer_marketing_platforms');
    }
}
