<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateScrapHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('scrap_histories', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('operation')->nullable();
			$table->string('model');
			$table->integer('model_id');
			$table->text('text', 65535)->nullable();
			$table->integer('created_by')->nullable();
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
		Schema::drop('scrap_histories');
	}

}
