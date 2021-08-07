<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPurchaseProductAddColumnOrderProductId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'purchase_products', function ( $table ) {
            $table->integer( 'order_product_id' )->unsigned()->nullable()->after( 'product_id' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table( 'purchase_products', function ( $table ) {
            $table->dropColumn( 'order_product_id' );
        });
    }
}
