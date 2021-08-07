<?php

namespace App\Library\DHL;

use App\Library\DHL\Response\TrackShipmentResponse;

/**
 * Get Rate request for DHL
 *
 *
 */
class TrackShipmentRequest extends APIAbstract
{

    private $awbNumbers;
    private $levelOfDetails = "ALL_CHECKPOINTS";

    protected $_stagingUrl    = 'https://wsbexpress.dhl.com/sndpt/glDHLExpressTrack';
    protected $_productionUrl = 'https://wsbexpress.dhl.com/sndpt/glDHLExpressTrack';

    public function __construct()
    {
        parent::__construct();
    }


    public function getAwbNumbers()
    {
        return $this->awbNumbers;
    }

    public function setAwbNumbers($numbers = [])
    {

        $this->awbNumbers = $numbers;
        return $this->awbNumbers;
    }

    public function getLevelOfDetails($level)
    {
        return $this->levelOfDetails;
    }

    public function setLevelOfDetails($level)
    {
        $this->levelOfDetails = $leval;
        return $this->levelOfDetails;
    }

    public function toXML()
    {
        $xml = new \XmlWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString("  ");
        //$xml->startDocument('1.0', 'UTF-8');

        $xml->startElement('soapenv:Envelope');
            $xml->writeAttribute('xmlns:wsu', "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd");
            $xml->writeAttribute('xmlns:wsse', "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd");
            $xml->writeAttribute('xmlns:soapenv', "http://schemas.xmlsoap.org/soap/envelope/");
            $xml->writeAttribute('xmlns:dhl', "http://www.dhl.com");
            $xml->writeAttribute('xmlns:trac', "http://scxgxtt.phx-dc.dhl.com/glDHLExpressTrack/providers/services/trackShipment");
            $xml->startElement('soapenv:Header');
                $xml->startElement('wsse:UsernameToken');
                    $xml->writeAttribute('wsu:Id', "Request");
                    $xml->writeElement('wsse:Username', $this->username);
                    $xml->startElement('wsse:Password');
                        $xml->writeAttribute('type', "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText");
                        $xml->text($this->password);
                    $xml->endElement();
                    $xml->startElement('wsse:Nonce');
                        $xml->writeAttribute('encodingtype', "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary");
                        $xml->text(md5(time()));
                    $xml->endElement();
                    $xml->writeElement('wsu:Created', date("Y-m-d"));
                $xml->endElement();
            $xml->endElement();
            $xml->startElement('soapenv:Body');
                $xml->startElement('trac:trackShipmentRequest');
                    $xml->startElement('trackingRequest');
                        $xml->startElement('dhl:TrackingRequest');
                            $xml->startElement('Request');
                                $xml->startElement('ServiceHeader');
                                    $xml->writeElement('MessageTime','2020-03-23T09:30:47-05:00');
                                    $xml->writeElement('MessageReference','ARYABHATTAARYABHATTAARYABHATTA');
                                $xml->endElement();
                            $xml->endElement();
                            $xml->startElement('AWBNumber');
                                if(!empty($this->awbNumbers)) {
                                    foreach($this->awbNumbers as $awbNumber) {
                                        $xml->writeElement('ArrayOfAWBNumberItem', $awbNumber);
                                    }
                                }
                            $xml->endElement();
                            $xml->writeElement('LevelOfDetails', $this->levelOfDetails);
                        $xml->endElement();
                    $xml->endElement();
                $xml->endElement();
            $xml->endElement();
        $xml->endElement();
        return $this->document = $xml->outputMemory();
    }

    public function call()
    {
        $result = $this->doCurlPost();

        return new TrackShipmentResponse($result);
    }

}
