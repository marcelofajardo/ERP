<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateErpEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('erp_events', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('event_name');
			$table->text('event_description', 65535)->nullable();
			$table->dateTime('start_date')->default('0000-00-00 00:00:00');
			$table->dateTime('end_date')->default('0000-00-00 00:00:00');
			$table->integer('type')->default(0);
			$table->text('brand_id', 65535)->nullable();
			$table->text('category_id', 65535)->nullable();
			$table->integer('number_of_person')->nullable()->default(100);
			$table->dateTime('product_start_date')->nullable()->default('0000-00-00 00:00:00');
			$table->dateTime('product_end_date')->nullable()->default('0000-00-00 00:00:00');
			$table->string('minute', 5)->nullable()->default('0');
			$table->string('hour', 5)->nullable()->default('0');
			$table->string('day_of_month', 5)->nullable()->default('0');
			$table->string('month', 5)->nullable()->default('0');
			$table->string('day_of_week', 5)->nullable()->default('0');
			$table->integer('created_by');
			$table->dateTime('next_run_date')->nullable()->default('0000-00-00 00:00:00');
			$table->integer('is_closed')->nullable()->default(0);
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
		Schema::drop('erp_events');
	}

}
