<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTranslationHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('product_translation_histories')) {
            Schema::create('product_translation_histories', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->unsignedInteger('product_translation_id')->index();
                $table->string('locale')->index();
                $table->string('title',255)->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
