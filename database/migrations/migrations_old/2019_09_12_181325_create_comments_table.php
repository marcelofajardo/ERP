<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('book_comments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('entity_id')->unsigned();
			$table->string('entity_type', 191);
			$table->text('text')->nullable();
			$table->text('html')->nullable();
			$table->integer('parent_id')->unsigned()->nullable();
			$table->integer('local_id')->unsigned()->nullable()->index();
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned()->nullable();
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
		Schema::drop('book_comments');
	}

}
