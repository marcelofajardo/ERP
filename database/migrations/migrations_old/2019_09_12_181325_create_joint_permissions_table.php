<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJointPermissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('joint_permissions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('role_id')->index();
			$table->string('entity_type', 191);
			$table->integer('entity_id');
			$table->string('action', 191)->index();
			$table->boolean('has_permission')->default(0)->index();
			$table->boolean('has_permission_own')->default(0)->index();
			$table->integer('created_by')->index();
			$table->index(['entity_id','entity_type']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('joint_permissions');
	}

}
