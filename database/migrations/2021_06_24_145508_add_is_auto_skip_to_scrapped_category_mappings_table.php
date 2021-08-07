<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsAutoSkipToScrappedCategoryMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scrapped_category_mappings', function (Blueprint $table) {
            $table->boolean('is_auto_skip')->defalut(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scrapped_category_mappings', function (Blueprint $table) {
            $table->dropColumn('is_auto_skip');

        });
    }
}
