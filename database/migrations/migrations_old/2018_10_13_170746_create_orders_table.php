<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create( 'orders', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->string( 'order_id' );
			$table->string( 'order_type' );
			$table->date( 'order_date' );
			$table->string( 'client_name' );
			$table->string( 'city' );
			$table->string( 'contact_detail' );
			$table->string( 'advance_detail' );
			$table->date( 'advance_date' );
			$table->string( 'balance_amount' );
			$table->string( 'sales_person' );
			$table->string( 'office_phone_number' );
			$table->string( 'order_status' );
			$table->date( 'estimated_delivery_date' );
			$table->string( 'note_if_any' );
			$table->softDeletes();
			$table->timestamps();
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists( 'orders' );
	}
}
