<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblSiteCroppedImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(!Schema::hasTable('site_cropped_images')) {

            Schema::create('site_cropped_images', function (Blueprint $table) {
                $table->unsignedBigInteger('website_id');
                $table->unsignedBigInteger('product_id');
                $table->primary(['website_id', 'product_id']);
            });
        }

        if(Schema::hasColumn('products', 'cropped_image_site_ids')){

            Schema::table('products', function (Blueprint $table) {
                    $table->dropColumn('cropped_image_site_ids');
            });

        }


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
