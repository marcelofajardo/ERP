<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChatbotIntentsAnnotationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('chatbot_intents_annotations', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('question_example_id');
			$table->integer('chatbot_keyword_id');
			$table->integer('chatbot_value_id')->nullable();
			$table->integer('start_char_range');
			$table->integer('end_char_range');
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
		Schema::drop('chatbot_intents_annotations');
	}

}
