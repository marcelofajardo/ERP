<?php

namespace App\Library\DHL;

use App\Library\DHL\Response\CreateShipmentResponse;

/**
 * Get Rate request for DHL
 *
 *
 */
class DeleteShipmentRequest extends APIAbstract
{

    private $pickupDate;
    private $pickupCountry;
    private $dispatchConfirmationNumber;

    public function __construct()
    {
        parent::__construct();
    }

    public function getPickupDate()
    {
        return $this->pickupDate;
    }

    public function setPickupDate($date)
    {
        $this->pickupDate = $date;
        return $this->pickupDate;
    }

    public function getPickupCountry()
    {
        return $this->pickupCountry;
    }

    public function setPickupCountry($country)
    {
        $this->pickupCountry = $country;
        return $this->pickupCountry;
    }

    public function getDispatchNumber()
    {
        return $this->dispatchConfirmationNumber;
    }

    public function setDispatchNumber($number)
    {
        $this->dispatchConfirmationNumber = $number;
        return $this->dispatchConfirmationNumber;
    }

    public function getRequestorName()
    {
        return $this->requestorName;
    }

    public function setRequestorName($name)
    {
        $this->requestorName = $name;
        return $this->requestorName;
    }

    public function getReason()
    {
        return $this->reason;
    }

    public function setReason($name)
    {
        $this->reason = $name;
        return $this->reason;
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
            $xml->writeAttribute('xmlns:del', "http://scxgxtt.phx-dc.dhl.com/euExpressRateBook/DeleteShipmentRequest");
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
                $xml->startElement('del:DeleteRequest');
                    $xml->writeElement('PickupDate', $this->pickupDate);
                    $xml->writeElement('PickupCountry', $this->pickupCountry);
                    $xml->writeElement('DispatchConfirmationNumber', $this->dispatchConfirmationNumber);
                    $xml->writeElement('RequestorName', $this->requestorName);
                    $xml->writeElement('Reason', $this->reason);
                $xml->endElement();
            $xml->endElement();
        $xml->endElement();
        //$xml->endDocument();
        return $this->document = $xml->outputMemory();
    }

    public function call()
    {
        $result = $this->doCurlPost();

        return new CreateShipmentResponse($result);
    }

}
