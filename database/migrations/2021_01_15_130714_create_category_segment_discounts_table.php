<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategorySegmentDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_segment_discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_id');
            $table->integer('category_segment_id');
            $table->integer('amount');
            $table->enum('amount_type', ['percentage']);
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
        Schema::dropIfExists('category_segment_discounts');
    }
}
