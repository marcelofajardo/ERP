<?php

namespace App\Library\DHL\Response;

abstract class ResponseAbstract
{
    public function __construct()
    {
    
    }

    /**
     * Check response has error or not?
     * @return Bool
     */

    public function hasError()
    {
        $notification = isset($this->response->Body->RateResponse->Provider->Notification)
        ? $this->response->Body->RateResponse->Provider->Notification : null;


        if(!empty($notification)) {
            foreach($notification->attributes() as $k => $ntf) {

                if($k == "code" && $ntf > 0) {
                    return true;
                }

                if((string)$ntf->code <= "0" && (string)$ntf->code == "") {
                    return false;
                } 
                return true;
            }
        }

        return true;
    }

    public function getErrorMessage()
    {
        $notification = isset($this->response->Body->RateResponse->Provider->Notification)
        ? $this->response->Body->RateResponse->Provider->Notification : null;

        if(!empty($notification)) {
            return [(string)$notification[0]->Message];
        }
    }
}
