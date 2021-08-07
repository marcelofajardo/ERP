<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lawyer_id')->nullable()->unsigned();
            $table->foreign('lawyer_id')->references('id')->on('lawyers')->onDelete('restrict')->onUpdate('restrict');
            $table->string('case_number')->nullable();
            $table->string('for_against')->nullable();
            $table->text('court_detail')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('resource')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->date('last_date')->nullable();
            $table->date('next_date')->nullable();
            $table->float('cost_per_hearing')->nullable();
            $table->text('remarks')->nullable();
            $table->text('other')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('cases');
    }
}
