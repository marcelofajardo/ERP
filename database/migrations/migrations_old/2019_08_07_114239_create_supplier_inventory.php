<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierInventory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'supplier_inventory', function ( Blueprint $table ) {
            $table->bigIncrements( 'id' );
            $table->string( 'supplier' );
            $table->string( 'sku' );
            $table->integer( 'inventory' );
            $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'supplier_inventory' );
    }
}
