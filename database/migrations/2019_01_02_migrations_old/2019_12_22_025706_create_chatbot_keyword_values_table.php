<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChatbotKeywordValuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('chatbot_keyword_values', function(Blueprint $table)
		{
			$table->engine = 'MyISAM';
			$table->integer('id', true);
			$table->string('value');
			$table->integer('chatbot_keyword_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('chatbot_keyword_values');
	}

}
