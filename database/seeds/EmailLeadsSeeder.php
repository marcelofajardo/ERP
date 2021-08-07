<?php

use Illuminate\Database\Seeder;
use App\Customer;
use App\EmailLead;


class EmailLeadsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		$customer = Customer::all();
		
		foreach($customer as $val)
		{
			$emailLead = new EmailLead();	
			$emailLead->email = $val->email;
			$emailLead->created_at = date('Y-m-d H:i:s');
			
			$emailLead->save();
		}
    }
}
