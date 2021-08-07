<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableStoreWebsiteProductAttributesPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('store_website_product_attributes',function(Blueprint $table) {
            $table->decimal('price')->default(0)->after('description');
            $table->decimal('discount')->default(0)->after('price');
            $table->string('discount_type')->default('percentage')->after('discount');
            $table->integer('updated_by')->nullable()->after('discount_type');
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
        Schema::table('store_website_product_attributes',function(Blueprint $table) {
            $table->dropField('price');
            $table->dropField('discount');
            $table->dropField('discount_type');
        });
    }
}
