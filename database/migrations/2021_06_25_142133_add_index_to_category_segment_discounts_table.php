<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToCategorySegmentDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_segment_discounts', function (Blueprint $table) {
            $table->index(['brand_id','category_segment_id' ]);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_segment_discounts', function (Blueprint $table) {
            $table->dropIndex(['brand_id','category_segment_id' ]);

        });
    }
}
