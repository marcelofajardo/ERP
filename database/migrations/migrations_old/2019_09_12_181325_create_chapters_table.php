<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChaptersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('chapters', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('book_id')->index();
			$table->string('slug', 191)->index();
			$table->text('name', 65535);
			$table->text('description', 65535);
			$table->integer('priority')->index();
			$table->timestamps();
			$table->integer('created_by')->index();
			$table->integer('updated_by')->index();
			$table->boolean('restricted')->default(0)->index();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('chapters');
	}

}
