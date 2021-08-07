<?php

namespace App\Library\Duty;

use Curl\Curl;

class SimplyDuty
{

    private $apiKey;
    private $urls = [
        'calculate_multiple' => 'https://www.api.simplyduty.com/api/duty/calculatemultiple',
        'calculate' => 'https://www.api.simplyduty.com/api/duty/calculate',
    ];

    public function __construct()
    {
        // $this->apiKey = env("SIMPLY_DUTY_API_KEY");
        $this->apiKey = config('env.SIMPLY_DUTY_API_KEY');
    }

    /**
     * Calculate multiple request
     * @param  originCountryCode
     * @param  destinationCountryCode
     * @param  items
     * @param  shipping
     * @param  insurance
     * @param  contractInsuranceType
     *
     */

    public function calculateMultiple(
        $originCountryCode,
        $destinationCountryCode,
        $items = [],
        $shipping = 0,
        $insurance = 0,
        $contractInsuranceType = "cIF"
    ) {
        $curl = new Curl();
        $curl->setHeader('x-api-key', $this->apiKey);

        $params = [
            'OriginCountryCode'      => $originCountryCode,
            'DestinationCountryCode' => $destinationCountryCode,
            'Items'                  => $items,
            'Shipping'               => $shipping,
            'Insurance'              => $insurance,
            'ContractInsuranceType'  => $contractInsuranceType,
        ];

        $curl->post($this->urls['calculate_multiple'], $params);

        if ($curl->error) {
            echo 'errorCode' . $curl->error_code;
            die();
        } else {
            $response = json_decode($curl->response);
        }

        $curl->close();

        return $response;
    }

    /**
     * Calculate multiple request
     * @param  originCountryCode
     * @param  destinationCountryCode
     * @param  items
     * @param  shipping
     * @param  insurance
     * @param  contractInsuranceType
     *
     */

    public function calculate(
        $originCountryCode,
        $destinationCountryCode,
        $hscode,
        $quantity = 1,
        $value = "0.00",
        $shipping = 0,
        $insurance = 0,
        $contractInsuranceType = "cIF",
        $originCurCode = "EUR",
        $destinationCurCode = "EUR"
    ) {
        $curl = new Curl();
        $curl->setHeader('x-api-key', $this->apiKey);

        $params = [
            'OriginCountryCode'         => $originCountryCode,
            'DestinationCountryCode'    => $destinationCountryCode,
            'HSCode'                    => $hscode,
            'Quantity'                  => $quantity,
            'Value'                     => $value,
            'Shipping'                  => $shipping,
            'Insurance'                 => $insurance,
            'ContractInsuranceType'     => $contractInsuranceType,
            'OriginCurrencyCode'        => $originCurCode,
            'DestinationCurrencyCode'   => $destinationCurCode
        ];

        $curl->post($this->urls['calculate'], $params);

        if ($curl->error) {
            echo 'errorCode' . $curl->error_code;
            die();
        } else {
            $response = json_decode($curl->response);
        }

        $curl->close();

        return $response;
    }


}
