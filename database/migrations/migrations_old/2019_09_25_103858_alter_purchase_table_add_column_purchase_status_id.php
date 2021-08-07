<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPurchaseTableAddColumnPurchaseStatusId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'purchases', function ( $table ) {
            $table->integer( 'purchase_status_id' )->unsigned()->nullable()->after( 'status' );
            $table->foreign('purchase_status_id')->references('id')->on('purchase_status')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table( 'purchases', function ( $table ) {
            $table->dropColumn( 'purchase_status_id' );
        });
    }
}
