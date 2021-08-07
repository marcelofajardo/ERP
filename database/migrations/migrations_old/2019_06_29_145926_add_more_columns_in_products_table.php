<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreColumnsInProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('manual_crop')->default(0);
            $table->boolean('is_manual_cropped')->default(0);
            $table->integer('manual_cropped_by')->default();
            $table->dateTime('manual_cropped_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'manual_crop',
                'is_manual_cropped',
                'manual_cropped_by',
                'manual_cropped_at'
            ]);
        });
    }
}
