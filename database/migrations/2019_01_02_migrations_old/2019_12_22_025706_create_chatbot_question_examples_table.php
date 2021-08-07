<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChatbotQuestionExamplesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('chatbot_question_examples', function(Blueprint $table)
		{
			$table->engine = 'MyISAM';
			$table->integer('id', true);
			$table->string('question');
			$table->integer('chatbot_question_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('chatbot_question_examples');
	}

}
