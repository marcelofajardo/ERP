<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChatMessagePhrasesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('chat_message_phrases', function(Blueprint $table)
		{
			$table->engine = 'MyISAM';
			$table->integer('id', true);
			$table->text('phrase', 65535);
			$table->integer('total')->default(0);
			$table->integer('word_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('chat_message_phrases');
	}

}
