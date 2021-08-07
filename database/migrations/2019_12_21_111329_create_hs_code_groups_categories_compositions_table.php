<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHsCodeGroupsCategoriesCompositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hs_code_groups_categories_compositions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hs_code_group_id');
            $table->integer('category_id');
            $table->string('composition');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hs_code_groups_categories_compositions');
    }
}
