<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGooglecampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('googlecampaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('campaign_name')->nullable();
            $table->decimal('budget_amount',15,2)->default(0);
            $table->string('start_date',25)->comment('format like 20201023');
            $table->string('end_date',25)->comment('format like 20201122');
            $table->tinyInteger('status')->default(0)->comment('1 enabled, 0 disabled');
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
        Schema::dropIfExists('googlecampaign');
    }
}
