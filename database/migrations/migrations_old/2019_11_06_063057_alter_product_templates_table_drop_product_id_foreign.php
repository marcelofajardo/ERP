<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductTemplatesTableDropProductIdForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_templates', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropIndex('product_templates_product_id_index');
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
            //
        });
    }
}
