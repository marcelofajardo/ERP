<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('book_tags', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('entity_id');
			$table->string('entity_type', 100);
			$table->string('name', 191)->index();
			$table->string('value', 191)->index();
			$table->integer('order')->index();
			$table->timestamps();
			$table->index(['entity_id','entity_type']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('book_tags');
	}

}
