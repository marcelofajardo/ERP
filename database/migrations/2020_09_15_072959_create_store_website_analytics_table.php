<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStoreWebsiteAnalyticsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('store_website_analytics', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('website');
			$table->string('account_id');
			$table->integer('view_id');
			$table->integer('store_website_id')->nullable()->index('store_website_id');
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
		Schema::drop('store_website_analytics');
	}

}
