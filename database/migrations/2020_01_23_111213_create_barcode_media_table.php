<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBarcodeMediaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('barcode_media', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('media_id')->nullable()->index('media_id');
			$table->string('type')->default('product')->index('type');
			$table->integer('type_id')->nullable()->index('type_id');
			$table->string('name')->index('name');
			$table->decimal('price', 10, 0)->default(0);
			$table->text('extra', 65535)->nullable();
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
		Schema::drop('barcode_media');
	}

}
