<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateErpLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('erp_leads', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('lead_status_id')->nullable()->unsigned()->index();
            $table->foreign('lead_status_id')->references('id')->on('erp_lead_status')->onDelete('cascade');

            $table->integer('customer_id')->nullable()->index();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

            $table->integer('product_id')->nullable()->unsigned()->index();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->integer('brand_id')->nullable()->unsigned()->index();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');

            $table->integer('category_id')->nullable()->unsigned()->index();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->string('color')->nullable();
            $table->string('size')->nullable();

            $table->decimal('min_price')->nullable()->default('0.00');
            $table->decimal('max_price')->nullable()->default('0.00');

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
        Schema::dropIfExists('erp_leads');
    }
}
