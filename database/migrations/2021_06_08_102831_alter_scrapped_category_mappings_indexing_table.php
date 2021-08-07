<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\ScrappedCategoryMapping;

class AlterScrappedCategoryMappingsIndexingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        ScrappedCategoryMapping::truncate();

        Schema::table('scrapped_category_mappings',function(Blueprint $table) {
            $table->index('name');
            $table->unique('name');
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
