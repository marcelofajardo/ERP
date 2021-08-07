<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Customer;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateCustomersMagento extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:magento-customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

            $options = array(
                'trace'              => true,
                'connection_timeout' => 120,
                'wsdl_cache'         => WSDL_CACHE_NONE,
            );

            $proxy     = new \SoapClient(config('magentoapi.url'), $options);
            $sessionId = $proxy->login(config('magentoapi.user'), config('magentoapi.password'));
            // $lastid    = Setting::get('lastid');
            //
            // $filter    = array(
            //     'complex_filter' => array(
            //         array(
            //             'key'   => 'order_id',
            //             'value' => array( 'key' => 'gt', 'value' => $lastid )
            //         )
            //     )
            // );

            $orderlist = $proxy->salesOrderList($sessionId);

            for ($j = 0; $j < sizeof($orderlist); $j++) {
                $results = json_decode(json_encode($proxy->salesOrderInfo($sessionId, $orderlist[$j]->increment_id)), true);
                $atts    = unserialize($results['items'][0]['product_options']);

                // if ( ! empty( $results['total_paid'] ) ) {
                //     $paid = $results['total_paid'];
                // } else {
                //     $paid = 0;
                // }
                //
                // $balance_amount = $results['base_grand_total'] - $paid;

                $full_name = $results['billing_address']['firstname'] . ' ' . $results['billing_address']['lastname'];

                $customer_phone = (int) str_replace(' ', '', $results['billing_address']['telephone']);
                $final_phone    = '';

                if ($customer_phone != null) {
                    if ($results['billing_address']['country_id'] == 'IN') {
                        if (strlen($customer_phone) <= 10) {
                            $customer_phone = '91' . $customer_phone;
                        }
                    }

                    $customer = Customer::where('phone', $customer_phone)->first();
                } else {
                    $customer = Customer::where('name', 'LIKE', "%$full_name%")->first();
                }

                if ($customer) {
                    dump("$j - UPDATING Customer");
                    $customer_id = $customer->id;

                    if ($customer_phone != null) {
                        $final_phone = $customer_phone;
                    }

                    // if ($customer->credit > 0) {
                    //     if (($balance_amount - $customer->credit) < 0) {
                    //         $left_credit = ($balance_amount - $customer->credit) * -1;
                    //         $balance_amount = 0;
                    //         $customer->credit = $left_credit;
                    //     } else {
                    //         $balance_amount -= $customer->credit;
                    //         $customer->credit = 0;
                    //     }
                    // }

                    if ($customer->email == '' || $customer->address == '' || $customer->city == '' || $customer->country == '' || $customer->pincode == '') {
                        $customer->name    = $full_name;
                        $customer->email   = $results['customer_email'];
                        $customer->address = $results['billing_address']['street'];
                        $customer->city    = $results['billing_address']['city'];
                        $customer->country = $results['billing_address']['country_id'];
                        $customer->pincode = $results['billing_address']['postcode'];
                        $customer->phone   = $final_phone;
                    }

                    $customer->save();
                } else {
                    dump("$j - NOT UPDATING");
                }
                // else {
                //     $customer = new Customer;
                //     $customer->name = $full_name;
                //     $customer->email = $results['customer_email'];
                //     $customer->address = $results['billing_address']['street'];
                //     $customer->city = $results['billing_address']['city'];
                //     $customer->country = $results['billing_address']['country_id'];
                //     $customer->pincode = $results['billing_address']['postcode'];
                //     $temp_number = [];
                //
                //     if ($customer_phone != null) {
                //         $temp_number['phone'] = $customer_phone;
                //     } else {
                //         $temp_number['phone'] = self::generateRandomString();
                //     }
                //
                //     $final_phone = self::validatePhone($temp_number);
                //     $customer->phone = $final_phone;
                //
                //     $customer->save();
                //
                //     $customer_id = $customer->id;
                // }
                dump('______________');
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
