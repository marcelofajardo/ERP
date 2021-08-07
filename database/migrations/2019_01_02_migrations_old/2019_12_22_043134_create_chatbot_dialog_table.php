<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChatbotDialogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('chatbot_dialogs', function(Blueprint $table)
		{
			$table->engine = 'MyISAM';
			$table->integer('id', true);
			$table->string('name');
			$table->string('title')->nullable();
			$table->integer('parent_id')->default(0);
			$table->string('match_condition');
			$table->string('workspace_id')->nullable();
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
		Schema::drop('chatbot_dialogs');
	}

}
