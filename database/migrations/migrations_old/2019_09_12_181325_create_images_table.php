<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('book_images', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 191);
			$table->string('url', 191);
			$table->timestamps();
			$table->integer('created_by');
			$table->integer('updated_by');
			$table->string('path', 400);
			$table->string('type', 191)->index();
			$table->integer('uploaded_to')->default(0)->index();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('book_images');
	}

}
