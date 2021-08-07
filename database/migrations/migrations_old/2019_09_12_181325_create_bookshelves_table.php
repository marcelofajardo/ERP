<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBookshelvesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bookshelves', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 200);
			$table->string('slug', 200)->index();
			$table->text('description', 65535);
			$table->integer('created_by')->nullable()->index();
			$table->integer('updated_by')->nullable()->index();
			$table->boolean('restricted')->default(0)->index();
			$table->integer('image_id')->nullable();
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
		Schema::drop('bookshelves');
	}

}
