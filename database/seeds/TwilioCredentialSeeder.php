<?php

use Illuminate\Database\Seeder;

class TwilioCredentialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\TwilioCredential::create([
           'twilio_email' => 'BUYING@AMOURINT.COM',
           'account_id' => 'AC5fc748210ade30f991cea8666c2c9580',
           'auth_token' => '518bd5f099967756a93962fb1e9904eb'
        ]);
    }
}
