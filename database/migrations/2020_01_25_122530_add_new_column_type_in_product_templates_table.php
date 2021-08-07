<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnTypeInProductTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_templates', function (Blueprint $table) {
            $table->integer('type')->after('is_processed')->default(0);
            $table->integer('category_id')->after('is_processed')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_templates', function (Blueprint $table) {
             $table->dropColumn('type');
             $table->dropColumn('category_id');
               
        });
    }
}
