<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function ($table) {
	        $table->string('sku');
	        $table->string('image',500);
        });

	    DB::update("ALTER TABLE products AUTO_INCREMENT = 100000;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('products', function($table) {
		    $table->dropColumn('sku');
		    $table->dropColumn('image');
	    });
    }
}
