<?php

namespace App\Library\DHL\Response;

/**
 * Get Rate response for DHL
 *
 *
 */

class TrackShipmentResponse extends ResponseAbstract
{

    public $response;

    public function __construct($response)
    {
        $this->response = $response;
        parent::__construct();
    }

    public function getTrackShipmentResponse()
    {
        $result = !empty($this->response->Body->trackShipmentRequestResponse->trackingResponse->TrackingResponse->AWBInfo) ? 
        $this->response->Body->trackShipmentRequestResponse->trackingResponse->TrackingResponse->AWBInfo : [];

        return $result;
    }

    public function getResponse()
    {
        $response = $this->getTrackShipmentResponse();
        
        $result = [];
        if(!empty($response)) {
            foreach($response->ArrayOfAWBInfoItem as $res) {
                $result[] = $res;
            }
        }
        return $result;
    }
}
