<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblSiteCroppedImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rejected_images', function (Blueprint $table) {
            $table->unsignedBigInteger('website_id');
            $table->unsignedBigInteger('product_id');
            $table->boolean('status')->nullable()->comment('1->approve, 0->rejected');
            $table->timestamps();
            $table->primary(['website_id', 'product_id']);
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
    }
}
