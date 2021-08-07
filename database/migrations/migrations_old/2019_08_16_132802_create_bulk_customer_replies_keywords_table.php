<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBulkCustomerRepliesKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulk_customer_replies_keywords', function (Blueprint $table) {
            $table->increments('id');
            $table->text('value');
            $table->string('text_type');
            $table->boolean('is_manual')->default(0);
            $table->integer('count');
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
        Schema::dropIfExists('bulk_customer_replies_keywords');
    }
}
