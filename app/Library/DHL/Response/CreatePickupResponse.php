<?php

namespace App\Library\DHL\Response;

/**
 * Get Rate response for DHL
 *
 *
 */

class CreatePickupResponse extends ResponseAbstract
{

    public $response;

    public function __construct($response)
    {
        $this->response = $response;
        parent::__construct();
    }

    /**
     * Check response has error or not?
     * @return Bool
     */

    public function hasError()
    {
        $notification = isset($this->response->Body->PickUpResponse->Notification)
        ? $this->response->Body->PickUpResponse->Notification : null;

        if (!empty($notification)) {
            foreach ($notification->attributes() as $k => $ntf) {

                if($k == "code" && $ntf > 0) {
                    return true;
                }
            
                if ((string) $ntf->code <= "0" && (string) $ntf->code == "") {
                    return false;
                }
                return true;
            }
        }

        return true;
    }

    public function getErrorMessage()
    {
        $notification = isset($this->response->Body->PickUpResponse->Notification)
        ? $this->response->Body->PickUpResponse->Notification : null;

        if (!empty($notification)) {
            return [(string) $notification[0]->Message];
        }
    }

    public function getPackageResult()
    {
        return isset($this->response->Body->PickUpResponse->PackagesResult->PackageResult)
        ? $this->response->Body->PickUpResponse->PackagesResult->PackageResult : null;
    }

    public function getReceipt()
    {
        $packageResult = $this->getPackageResult();
        // check if service is not empty then
        $resCharges = [];

        if (!empty($packageResult) ) {
            $resCharges["message"] = (string)$packageResult->Message;
            $resCharges["code"]     = (string)$packageResult->code;
        }

        return $resCharges;
    }
}
