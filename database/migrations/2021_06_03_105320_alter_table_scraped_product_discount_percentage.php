<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableScrapedProductDiscountPercentage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('scraped_products',function(Blueprint $table) {
            $table->decimal('discounted_percentage', 8, 2)->default(0.00)->after('discounted_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('scraped_products',function(Blueprint $table) {
            $table->dropField('discounted_percentage');
        });
    }
}
