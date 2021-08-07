<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('template_no')->default(0);
            $table->string('product_title')->nullable();
            $table->integer('brand_id')->nullable();
            $table->char('currency',3)->nullable();
            $table->decimal('price', 8, 2)->default(0.00);
            $table->decimal('discounted_price', 8, 2)->default(0.00);
            $table->integer('product_id')->nullable()->unsigned()->index();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->integer('is_processed')->nullable()->default(0);
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
        Schema::dropIfExists('product_templates');
    }
}
