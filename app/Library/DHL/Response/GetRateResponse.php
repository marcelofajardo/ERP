<?php

namespace App\Library\DHL\Response;

/**
 * Get Rate response for DHL
 *
 *
 */

class GetRateResponse extends ResponseAbstract
{

    public $response;

    public function __construct($response)
    {
        $this->response = $response;
        parent::__construct();
    }

    public function getService()
    {
        return isset($this->response->Body->RateResponse->Provider->Service)
        ? $this->response->Body->RateResponse->Provider->Service : null;
    }

    public function getTotalNet()
    {

        $service = $this->getService();

        if (!empty($service)) {
            return ($service->TotalNet) ?: [];
        }

        return [];
    }

    public function getTotal()
    {
        $totalNet = $this->getTotalNet();

        return isset($totalNet->Amount) ? $totalNet->Amount : 0;
    }

    public function getCurrency()
    {
        $totalNet = $this->getTotalNet();
        return isset($totalNet->Currency) ? $totalNet->Currency : "";
    }

    public function getChargesBreakDown()
    {
        $services = $this->getService();
        //echo "<pre>"; print_r($services);  echo "</pre>";die;
        
        // check if service is not empty then
        
        $servicesR  = [];
        if (!empty($services)) {
            foreach($services as $service) {
                $resCharges = [];
                if(isset($service->CustomerAgreementInd) && $service->CustomerAgreementInd == "N") {
                    $charges = !empty($service->Charges) ? $service->Charges : [];
                    if (!empty($charges)) {
                        foreach ($charges->Charge as $key => $value) {
                            $resCharges["charges"][] = [
                                "name"   => (string)$value->ChargeName,
                                "amount" => (string)$value->ChargeAmount,
                            ];
                        }
                    }
                    $resCharges["amount"]             = (string) $service->TotalNet->Amount;
                    $resCharges["currency"]           = (string) $service->TotalNet->Currency;
                    $resCharges["delivery_time"]      = (string) date("Y-m-d H:i:s",strtotime($service->DeliveryTime));
                    $resCharges["service_type"]       = (string) $service->ServiceName;
                    $resCharges["total_transit_days"] = (string) $service->TotalTransitDays;
                    $servicesR[] = $resCharges;
                }
            }
        }

        return $servicesR;
    }
}
