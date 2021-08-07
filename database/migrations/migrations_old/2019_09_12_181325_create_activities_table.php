<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActivitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('book_activities', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('key', 191);
			$table->text('extra', 65535);
			$table->integer('book_id')->index();
			$table->integer('user_id')->index();
			$table->integer('entity_id')->index();
			$table->string('entity_type', 191);
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
		Schema::drop('book_activities');
	}

}
