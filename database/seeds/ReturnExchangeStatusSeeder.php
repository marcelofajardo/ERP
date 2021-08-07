<?php

use Illuminate\Database\Seeder;

class ReturnExchangeStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->_createStatuses();
    }
	
	private function _createStatuses()
    {
        // Set current statuses
        $arrStatus = [
            1 => 'Return request received from customer',
			2 => 'Return request sent to courier',
			3 => 'Return pickup',
			4 => 'Return received in warehouse',
			5 => 'Return accepted',
			6 => 'Return rejected',
        ];

        // Insert all of them
        foreach ($arrStatus as $status) {
            DB::table('return_exchange_statuses')->insert(['status_name' => trim($status)]);
        }
    }
}
