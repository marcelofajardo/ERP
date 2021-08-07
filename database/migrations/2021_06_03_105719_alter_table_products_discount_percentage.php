<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableProductsDiscountPercentage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('products',function(Blueprint $table) {
            $table->decimal('discounted_percentage', 8, 2)->default(0.00)->after('price_eur_discounted');
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
        Schema::table('products',function(Blueprint $table) {
            $table->dropField('discounted_percentage');
        });
    }
}
