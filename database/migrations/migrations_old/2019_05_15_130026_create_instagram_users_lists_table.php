<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstagramUsersListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instagram_users_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->string('user_id');
            $table->text('image_url');
            $table->text('bio');
            $table->integer('rating');
            $table->integer('location_id');
            $table->text('because_of');
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
        Schema::dropIfExists('instagram_users_lists');
    }
}
