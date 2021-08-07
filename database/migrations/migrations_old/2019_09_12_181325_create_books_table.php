<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBooksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('books', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 191);
			$table->string('slug', 191)->index();
			$table->text('description', 65535);
			$table->timestamps();
			$table->integer('created_by')->index();
			$table->integer('updated_by')->index();
			$table->boolean('restricted')->default(0)->index();
			$table->integer('image_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('books');
	}

}
