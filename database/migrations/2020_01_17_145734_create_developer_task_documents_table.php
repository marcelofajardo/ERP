<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeveloperTaskDocumentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('developer_task_documents', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('subject');
			$table->text('description', 65535)->nullable();
			$table->integer('created_by')->nullable();
			$table->timestamps();
			$table->integer('developer_task_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('developer_task_documents');
	}

}
