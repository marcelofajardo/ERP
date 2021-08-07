<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToBookshelvesBooksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bookshelves_books', function(Blueprint $table)
		{
			$table->foreign('book_id')->references('id')->on('books')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('bookshelf_id')->references('id')->on('bookshelves')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bookshelves_books', function(Blueprint $table)
		{
			$table->dropForeign('bookshelves_books_book_id_foreign');
			$table->dropForeign('bookshelves_books_bookshelf_id_foreign');
		});
	}

}
