<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePlansTable4894567 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('plans', function (Blueprint $table) {
            if (!Schema::hasColumn('plans', 'strength')) //check the column
            {
                $table->text('strength')->nullable();
            }

            if (!Schema::hasColumn('plans', 'weakness')) //check the column
            {
                $table->text('weakness')->nullable();
            }


            if (!Schema::hasColumn('plans', 'opportunity')) //check the column
            { 
                $table->text('opportunity')->nullable();
            }


            if (!Schema::hasColumn('plans', 'threat')) //check the column
            {
                $table->text('threat')->nullable();
            }

            if (!Schema::hasColumn('plans', 'category')) //check the column
            {
                $table->text('category')->nullable();
            }
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
    }
}
