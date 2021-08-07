<?php

use Illuminate\Database\Seeder;
use App\ReferralProgram;
class RefrerralProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $store_websites = DB::table('store_websites')->select('id','website')->groupBy('website')->get();
        if($store_websites){
            foreach($store_websites as $website){
                ReferralProgram::updateOrCreate(
                    ['uri'=>$website->website],
                    [
                    'name'=>'signup_referral',
                    'uri'=>"$website->website",
                    'credit'=>100,
                    'currency'=>'EUR',
                    'lifetime_minutes'=>10080,
                    'store_website_id'=>"$website->id",
                    ]
                    );
            }
        }
    }
}
