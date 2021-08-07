<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePageRevisionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('page_revisions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('page_id')->index();
			$table->string('name', 191);
			$table->text('html');
			$table->text('text');
			$table->integer('created_by');
			$table->timestamps();
			$table->string('slug', 191)->index();
			$table->string('book_slug', 191)->index();
			$table->string('type', 191)->default('version')->index();
			$table->text('markdown');
			$table->string('summary', 191)->nullable();
			$table->integer('revision_number')->index();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('page_revisions');
	}

}
