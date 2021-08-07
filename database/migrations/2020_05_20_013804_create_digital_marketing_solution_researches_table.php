<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDigitalMarketingSolutionResearchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('digital_marketing_solution_researches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject');
            $table->text('description')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('priority');
            $table->integer('digital_marketing_solution_id');
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
        Schema::dropIfExists('digital_marketing_solution_researches');
    }
}
