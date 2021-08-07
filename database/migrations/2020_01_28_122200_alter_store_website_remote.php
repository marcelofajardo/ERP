<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterStoreWebsiteRemote extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('store_websites', function(Blueprint $table)
		{
			$table->string('remote_software')->nullable()->after("is_published");
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('store_websites', function(Blueprint $table)
		{
			$table->dropColumn('remote_software');
		});
	}

}
