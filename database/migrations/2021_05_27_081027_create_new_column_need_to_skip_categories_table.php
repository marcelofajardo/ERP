<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewColumnNeedToSkipCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_updated_attribute_histories',function(Blueprint $table) {
            $table->integer('need_to_skip')->nullable()->default(0)->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("user_updated_attribute_histories",function(Blueprint $table) {
            $table->dropField("need_to_skip");
        });
    }
}
