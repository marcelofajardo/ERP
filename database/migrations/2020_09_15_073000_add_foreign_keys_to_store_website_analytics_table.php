<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToStoreWebsiteAnalyticsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('store_website_analytics', function(Blueprint $table)
		{
			$table->foreign('store_website_id', 'store_website_analytics_ibfk_1')->references('id')->on('store_websites')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('store_website_analytics', function(Blueprint $table)
		{
			$table->dropForeign('store_website_analytics_ibfk_1');
		});
	}

}
