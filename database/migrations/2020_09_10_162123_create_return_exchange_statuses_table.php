<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnExchangeStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_exchange_statuses', function (Blueprint $table) {		
            $table->increments('id');
			$table->string('status_name');
            $table->timestamps();
        });
		
		$arrStatus = [
            1 => 'Return request received from customer',
			2 => 'Return request sent to courier',
			3 => 'Return pickup',
			4 => 'Return received in warehouse',
			5 => 'Return accepted',
			6 => 'Return rejected',
        ];
		       
        foreach ($arrStatus as $status) {
            DB::table('return_exchange_statuses')->insert(['status_name' => trim($status)]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('return_exchange_statuses');
    }
}
