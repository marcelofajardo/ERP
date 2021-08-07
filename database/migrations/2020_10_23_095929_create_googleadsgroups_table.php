<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoogleadsgroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('googleadsgroups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('adgroup_google_campaign_id')->nullable();
            $table->unsignedBigInteger('google_adgroup_id')->nullable();
            $table->string('ad_group_name')->nullable();
            $table->decimal('bid',15,2)->nullable();
            $table->string('status')->nullable()->comment('E.g UNKNOWN, ENABLED,PAUSED,REMOVED');
            $table->text('adgroup_response')->nullable();
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
        Schema::dropIfExists('googleadsgroups');
    }
}
