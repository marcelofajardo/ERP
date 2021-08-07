<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStoreWebsitesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('store_websites', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('website');
			$table->string('title')->nullable()->index('title');
			$table->text('description', 65535)->nullable();
			$table->integer('is_published')->default(0)->index('is_published');
			$table->softDeletes();
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
		Schema::drop('store_websites');
	}

}
