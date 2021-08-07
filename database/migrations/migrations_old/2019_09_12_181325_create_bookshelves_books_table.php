<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBookshelvesBooksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bookshelves_books', function(Blueprint $table)
		{
			$table->integer('bookshelf_id')->unsigned();
			$table->integer('book_id')->unsigned()->index('bookshelves_books_book_id_foreign');
			$table->integer('order')->unsigned();
			$table->primary(['bookshelf_id','book_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bookshelves_books');
	}

}
