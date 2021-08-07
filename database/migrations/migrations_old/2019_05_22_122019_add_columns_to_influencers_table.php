<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToInfluencersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('influencers', function (Blueprint $table) {
            $table->string('username')->nullable()->change();
            $table->string('brand_name')->nullable();
            $table->string('blogger')->nullable();
            $table->string('first_post')->nullable();
            $table->string('second_post')->nullable();
            $table->string('city')->nullable();
            $table->string('deals')->nullable();
            $table->string('details')->nullable();
            $table->text('list_first_post')->nullable();
            $table->text('list_second_post')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('influencers', function (Blueprint $table) {
            //
        });
    }
}
