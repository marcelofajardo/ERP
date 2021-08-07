<?php

namespace App\Console\Commands;

use App\ColdLeads;
use App\CronJobReport;
use App\Customer;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MoveColdLeadsToCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cold-leads:move-to-customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move cold leads to the customer databas';

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
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            // Get cold leads
            $coldLeads = ColdLeads::where('is_imported', 1)->where('customer_id', null)->inRandomOrder()->get();

            // Set count to 0 and maxcount to 50
            $count    = 0;
            $maxCount = 500000;

            // Get all numbers from config
            $config = \Config::get("apiwha.instances");

            // Loop over numbers
            $arrCustomerNumbers = [];
            foreach ($config as $whatsAppNumber => $arrNumber) {
                if ($arrNumber['customer_number']) {
                    $arrCustomerNumbers[] = $arrNumber['customer_number'];
                }
            }

            // Loop over coldLeads
            if ($coldLeads !== null) {
                foreach ($coldLeads as $coldLead) {
                    // Reached maxCount
                    if ($count >= $maxCount) {
                        return;
                    }

                    // Add cold lead to customers table
                    if (!$coldLead->customer && !$coldLead->whatsapp) {
                        // Check for existing customer
                        $customer = Customer::where('phone', $coldLead->platform_id)->get();

                        // Nothing found?
                        if ($customer == null && !empty($coldLead->name)) {
                            // Create new customer
                            $customer                  = new Customer();
                            $customer->name            = $coldLead->name;
                            $customer->phone           = $coldLead->platform_id;
                            $customer->whatsapp_number = $arrCustomerNumbers[rand(0, count($arrCustomerNumbers) - 1)];
                            $customer->city            = $coldLead->address;
                            $customer->country         = 'IN';
                            try {
                                $customer->save();
                            } catch (\Exception $e) {
                                echo $e->getMessage();
                                $coldLead->customer_id = 1;
                                $coldLead->save();
                            }

                            if (!empty($customer->id)) {
                                $coldLead->customer_id = $customer->id;
                                $coldLead->save();
                            }
                        } else {
                            $coldLead->customer_id = 1;
                            $coldLead->save();
                        }

                        $count++;
                    }

                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
