<?php

namespace App\Library\DHL\Response;

/**
 * Get Rate response for DHL
 *
 *
 */

class CreateShipmentResponse extends ResponseAbstract
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
        $notification = isset($this->response->Body->ShipmentResponse->Notification)
        ? $this->response->Body->ShipmentResponse->Notification : null;

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
        $notification = isset($this->response->Body->ShipmentResponse->Notification)
        ? $this->response->Body->ShipmentResponse->Notification : null;

        if (!empty($notification)) {
            return [(string) $notification[0]->Message];
        }
    }

    public function getPackageResult()
    {
        return isset($this->response->Body->ShipmentResponse->PackagesResult->PackageResult)
        ? $this->response->Body->ShipmentResponse->PackagesResult->PackageResult : null;
    }

    public function getLabel()
    {
        return isset($this->response->Body->ShipmentResponse->LabelImage) 
        ? $this->response->Body->ShipmentResponse->LabelImage : null;
    }

    public function getIdentificationNumber()
    {
        return isset($this->response->Body->ShipmentResponse->ShipmentIdentificationNumber) 
        ? (string)$this->response->Body->ShipmentResponse->ShipmentIdentificationNumber : null;
    }

    public function getReceipt()
    {
        $packageResult = $this->getPackageResult();
        $label         = $this->getLabel();
        // check if service is not empty then
        $resCharges = [];

        if (!empty($packageResult) && !empty($label)) {
            $resCharges["tracking_number"] = (string)$packageResult->TrackingNumber;
            $resCharges["label_image"]     = (string)$label->GraphicImage;
            $resCharges["label_format"]    = (string)$label->LabelImageFormat;
            $resCharges["tracking_number"] = $this->getIdentificationNumber();
        }

        return $resCharges;
    }
}
