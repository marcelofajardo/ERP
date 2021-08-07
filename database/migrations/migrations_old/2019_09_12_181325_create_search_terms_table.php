<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSearchTermsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('search_terms', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('term', 200)->index();
			$table->string('entity_type', 100)->index();
			$table->integer('entity_id');
			$table->integer('score')->index();
			$table->index(['entity_type','entity_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('search_terms');
	}

}
