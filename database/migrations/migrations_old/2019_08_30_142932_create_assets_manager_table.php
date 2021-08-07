<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets_manager', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('asset_type');
            $table->integer('category_id')->unsigned();
            $table->string('purchase_type');
            $table->string('payment_cycle');
            $table->float('amount', 8, 2);
            $table->boolean('archived');
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
        Schema::dropIfExists('assets_manager');
    }
}
