<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEntityPermissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('entity_permissions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('restrictable_id');
			$table->string('restrictable_type', 191);
			$table->integer('role_id')->index('restrictions_role_id_index');
			$table->string('action', 191)->index('restrictions_action_index');
			$table->index(['restrictable_id','restrictable_type'], 'restrictions_restrictable_id_restrictable_type_index');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('entity_permissions');
	}

}
