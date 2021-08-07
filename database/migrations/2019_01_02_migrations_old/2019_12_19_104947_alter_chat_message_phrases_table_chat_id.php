<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterChatMessagePhrasesTableChatId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('chat_message_phrases', function(Blueprint $table)
		{
			$table->integer('chat_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('chat_message_phrases', function (Blueprint $table) {
            $table->dropColumn('chat_id');
        });
	}

}
