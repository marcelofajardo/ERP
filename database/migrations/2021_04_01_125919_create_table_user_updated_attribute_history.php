<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserUpdatedAttributeHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create("user_updated_attribute_histories",function(Blueprint  $table) {
            $table->increments('id');
            $table->string('old_value')->nullable();
            $table->string('new_value')->nullable();
            $table->string('attribute_name')->default("compositions")->index();
            $table->string("attribute_id")->nullable()->index();
            $table->integer("user_id")->index();
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
        //
        Schema::dropIfExists('user_updated_attribute_histories');
    }
}
