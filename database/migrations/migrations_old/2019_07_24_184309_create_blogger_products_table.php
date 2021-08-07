<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBloggerProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogger_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('blogger_id')->unsigned();
            $table->foreign('blogger_id')->references('id')->on('bloggers')->onDelete('restrict')->onUpdate('restrict');
            $table->integer('brand_id')->unsigned();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('restrict')->onUpdate('restrict');
            $table->date('shoot_date')->nullable();
            $table->date('first_post')->nullable();
            $table->date('second_post')->nullable();
            $table->integer('first_post_likes')->nullable();
            $table->integer('first_post_engagement')->nullable();
            $table->integer('first_post_response')->nullable();
            $table->integer('first_post_sales')->nullable();
            $table->integer('second_post_likes')->nullable();
            $table->integer('second_post_engagement')->nullable();
            $table->integer('second_post_response')->nullable();
            $table->integer('second_post_sales')->nullable();
            $table->string('city')->nullable();
            $table->string('initial_quote')->nullable();
            $table->string('final_quote')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->text('images')->nullable();
            $table->text('remarks')->nullable();

            $table->text('other')->nullable();
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
        Schema::dropIfExists('blogger_products');
    }
}
