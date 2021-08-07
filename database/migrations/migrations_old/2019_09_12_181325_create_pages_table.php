<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pages', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('book_id')->index();
			$table->integer('chapter_id')->index();
			$table->string('name', 191);
			$table->string('slug', 191)->index();
			$table->text('html');
			$table->text('text');
			$table->integer('priority')->index();
			$table->timestamps();
			$table->integer('created_by')->index();
			$table->integer('updated_by')->index();
			$table->boolean('restricted')->default(0)->index();
			$table->boolean('draft')->default(0)->index();
			$table->text('markdown');
			$table->integer('revision_count');
			$table->boolean('template')->default(0)->index();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pages');
	}

}
