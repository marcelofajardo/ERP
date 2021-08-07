<?php

namespace App\Console\Commands;

use App\Customer;
use App\Helpers\InstantMessagingHelper;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CustomerPhoneNumberCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:phone {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks if the customer phone number is valid';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $report = \App\CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);
            $type = $this->argument('type');

            $customers = Customer::where('do_not_disturb', 1)->get();
            if ($type == 'test') {
                foreach ($customers as $customer) {
                    if ($customer->phone == null) {
                        continue;
                    }
                    //check if existing customer is on not invalid list
                    $result = substr($customer->phone, 0, 1);
                    if ($result == '-') {
                        continue;
                    }
                    $result = InstantMessagingHelper::customerPhoneCheck($customer->phone, 0);
                    if ($result == false) {
                        dump('Customer Name :' . $customer->name . "\n Customer ID: " . $customer->id . "\nPhone Number Not Valid:" . $customer->phone . "\n");
                    }
                }
            } elseif ($type == 'run') {
                foreach ($customers as $customer) {

                    if ($customer->phone == null) {
                        continue;
                    }
                    //check if existing customer is on not invalid list
                    $result = substr($customer->phone, 0, 1);
                    if ($result == '-') {
                        continue;
                    }
                    $result = InstantMessagingHelper::customerPhoneCheck($customer->phone, 1);
                    if ($result == false) {
                        dump('Customer Name :' . $customer->name . "\n Customer ID: " . $customer->id . "\nPhone Number Not Valid:" . $customer->phone . "\n");
                    }
                }
            } else {
                dump('Please use test or run');
            }
            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }

    }
}
