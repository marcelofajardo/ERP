<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PriceOverrideTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_overrides', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->string('country_code')->nullable();
            $table->enum('type',['PERCENTAGE', 'FIXED'])->default('PERCENTAGE');
            $table->enum('calculated',['+', '-'])->default('+');
            $table->decimal('value')->default("0.00");
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
        Schema::dropIfExists('price_overrides');
    }
}
