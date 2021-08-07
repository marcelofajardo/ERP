<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Customer;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncCustomersFromMagento extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:erp-magento-customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync customers in Magento with ERP customers';

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

            $proxy = new \SoapClient(config('magentoapi.url'), $options);
            try {
                $sessionId = $proxy->login(config('magentoapi.user'), config('magentoapi.password'));

                //Get customer list from magento
                $magentoCustomers = json_decode(json_encode($proxy->customerCustomerList($sessionId)), true);

                //Loop through customers
                if (count($magentoCustomers) > 0) {
                    foreach ($magentoCustomers as $k => $customer) {
                        $customerId              = $customer['customer_id'];
                        $customerEmail           = $customer['email'];
                        $magentoCustomersAddress = json_decode(json_encode($proxy->customerAddressList($sessionId, $customerId)), true);

                        if (count($magentoCustomersAddress) > 0) {
                            foreach ($magentoCustomersAddress as $ck => $customerAddress) {
                                if (trim($customerAddress['telephone']) != '') {
                                    $customerPhone = $this->formatPhonenumber($customerAddress['telephone'], $customerAddress['country_id']);

                                    //Check if customer exists in ERP, with email and phone number
                                    if (!$this->checkERPCustomer($customerEmail, $customerPhone)) {

                                        $customerInfo = $this->setCustomer($customer, $customerAddress);

                                        //Add new customer to ERP
                                        $this->addNewCustomerToERP($customerInfo);
                                    }
                                }
                            }
                        }
                    }
                }
            } catch (\SoapFault $fault) {
                // can't connect magento API server
                dump("Can't connect Magento via SOAP: " . $fault->getMessage());
                \App\CronJob::insertLastError($this->signature, $fault->getMessage());
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }

    /**
     * Check if customer exist in ERP.
     *
     * @return boolean
     */
    public function checkERPCustomer($email, $phonenumber)
    {
        //$phone number might need format.. will have to check database for properly matching the phonenumber
        $customer = Customer::where('email', $email)->where('phone', $phonenumber)->first();

        return ($customer) ? true : false;
    }

    /**
     * Form customer data from magento.
     *
     * @return array
     */
    public function setCustomer($customerInfo, $customerAddress)
    {
        $customer            = [];
        $customer['name']    = $customerInfo['firstname'] . ' ' . $customerInfo['lastname'];
        $customer['email']   = $customerInfo['email'];
        $customer['address'] = $customerAddress['street'];
        $customer['city']    = $customerAddress['city'];
        $customer['country'] = $customerAddress['country_id'];
        $customer['pincode'] = $customerAddress['postcode'];
        $customer['phone']   = $this->formatPhonenumber($customerAddress['telephone'], $customerAddress['country_id']);

        return $customer;
    }

    /**
     * Add new customer into ERP.
     *
     * @return boolean
     */
    public function addNewCustomerToERP($customerInfo)
    {
        $customer          = new Customer;
        $customer->name    = $customerInfo['name'];
        $customer->email   = $customerInfo['email'];
        $customer->address = $customerInfo['address'];
        $customer->city    = $customerInfo['city'];
        $customer->country = $customerInfo['country'];
        $customer->pincode = $customerInfo['pincode'];
        $customer->phone   = $customerInfo['phone'];

        $customer->save();
    }

    /**
     * Format customer phone number
     *
     * @return string
     */
    public function formatPhonenumber($phonenumber, $country_id)
    {
        $customerPhone = ltrim(ltrim(str_replace(' ', '', $phonenumber), '+'), '00');
        //not sure if we need below functionality, but its been used in other part of application
        /*$customerPhone = (int) str_replace(' ', '', $phonenumber);
        $customerPhone = str_replace(' ', '', $phonenumber);
        if ($country_id == 'IN') {
        if (strlen($customerPhone) <= 10) {
        $customerPhone = '91' . $customerPhone;
        }
        }*/

        return $customerPhone;
    }
}
