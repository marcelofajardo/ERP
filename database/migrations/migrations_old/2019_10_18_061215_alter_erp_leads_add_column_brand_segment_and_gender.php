<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterErpLeadsAddColumnBrandSegmentAndGender extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('erp_leads', function (Blueprint $table) {
            $table->string('brand_segment')->nullable()->after('max_price');
            $table->string('gender')->nullable()->after('brand_segment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('erp_leads', function (Blueprint $table) {
            $table->dropColumn('brand_segment');
            $table->dropColumn('gender');
        });
    }
}
