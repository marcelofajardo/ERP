<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoogleTraslationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_traslation_settings', function (Blueprint $table) {
            // email , account_json , status, last_note , created_at
            $table->increments('id');
            $table->string('email')->nullable()->index();
            $table->text('account_json')->nullable();
            $table->string('status')->nullable();
            $table->text('last_note')->nullable();
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
        Schema::dropIfExists('google_traslation_settings');
    }
}
