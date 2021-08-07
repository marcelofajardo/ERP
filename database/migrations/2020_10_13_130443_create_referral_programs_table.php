<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferralProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referral_programs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->string('uri');
            $table->double('credit',8,2)->index();
            $table->string('currency');
            $table->integer('lifetime_minutes')->default(7 * 24 * 60);
            $table->string('store_website_id');
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
        Schema::dropIfExists('referral_programs');
    }
}
