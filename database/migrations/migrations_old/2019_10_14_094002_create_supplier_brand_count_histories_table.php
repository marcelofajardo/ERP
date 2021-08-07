<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierBrandCountHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_brand_count_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('supplier_brand_count_id');
            $table->integer('supplier_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->string('cnt')->nullable();
            $table->longtext('url')->nullable();
            $table->integer('brand_id')->nullable();
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
        Schema::dropIfExists('supplier_brand_count_histories');
    }
}
